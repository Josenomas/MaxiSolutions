<?php

namespace App\Http\Controllers;


use App\Models\Pago;
use App\Models\Solicitud;
use App\Models\SecurityLog;
use App\Services\FlowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PagoRecibidoMail;
class FlowController extends Controller
{
    private $flowService;

    public function __construct(FlowService $flowService)
    {
        $this->flowService = $flowService;
    }

    /**
     * Inicia el proceso de pago con Flow
     */
    public function pay(Request $request, $solicitudId)
    {
        try {
            $solicitud = Solicitud::findOrFail($solicitudId);

            // Crear orden de compra única
            $buyOrder = 'FLOW-' . $solicitudId . '-' . time();

            // Crear registro de pago pendiente
            $pago = Pago::create([
                'solicitud_id' => $solicitud->id,
                'usuario_id' => auth()->id(),
                'monto' => $request->input('monto', 0),
                'metodo_pago' => 'flow',
                'estado' => 'pendiente',
                'buy_order' => $buyOrder,
                'response_data' => []
            ]);
            
            // Log intento de pago
            SecurityLog::logPaymentAttempt(auth()->id(), $solicitud->id, 'flow', $request->input('monto', 0));

            // Preparar parámetros para Flow
            $params = [
                'commerceOrder' => $buyOrder,
                'subject' => 'Pago Solicitud #' . $solicitud->id . ' - MaxiSolutions',
                'currency' => 'CLP',
                'amount' => $request->input('monto', 0),
                'email' => $solicitud->email_cliente,
                'urlConfirmation' => route('flow.confirm', [], true), // Forzar HTTPS
                'urlReturn' => route('flow.return', [], true), // Forzar HTTPS
                'optional' => json_encode([
                    'solicitud_id' => $solicitud->id,
                    'pago_id' => $pago->id
                ])
            ];

            // Crear pago en Flow
            $response = $this->flowService->createPayment($params);

            if (isset($response['token']) && isset($response['url'])) {
                // Guardar token
                $pago->update([
                    'token' => $response['token'],
                    'response_data' => $response
                ]);

                // Obtener URL de pago
                $paymentUrl = $this->flowService->getPaymentUrl($response['token']);

                // Redirigir a Flow
                return view('pagos.redirect-flow', [
                    'paymentUrl' => $paymentUrl,
                    'token' => $response['token']
                ]);
            } else {
                throw new \Exception('Error al crear el pago en Flow');
            }

        } catch (\Exception $e) {
            Log::error('Error en Flow payment: ' . $e->getMessage());

            return redirect()
                ->route('pago.checkout', $solicitudId)
                ->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Callback de confirmación de Flow (server-to-server)
     */
    public function confirm(Request $request)
    {
        try {
            // Verificar firma
            if (!$this->flowService->verifySignature($request->all())) {
                Log::error('Firma inválida en callback de Flow');
                return response('Firma inválida', 401);
            }

            $token = $request->input('token');

            // Obtener estado del pago
            $paymentStatus = $this->flowService->confirmPayment($token);

            // Buscar el pago
            $pago = Pago::where('token', $token)->firstOrFail();

            // Actualizar estado según respuesta de Flow
            if (isset($paymentStatus['status'])) {
                $estado = $this->mapFlowStatus($paymentStatus['status']);

                $pago->update([
                    'estado' => $estado,
                    'referencia_pago' => $paymentStatus['flowOrder'] ?? null,
                    'response_data' => $paymentStatus,
                    'fecha_confirmacion' => now()
                ]);

                // Si el pago fue exitoso, actualizar la solicitud
                if ($estado === 'aprobado') {
                    $pago->solicitud->update([
                        'estado' => 'aceptado'
                    ]);
                    
                    // Enviar notificación por email
                    if ($pago->solicitud->usuario) {
                        Mail::to($pago->solicitud->usuario->email)->send(new PagoRecibidoMail($pago));
                    }
                }

                Log::info('Pago Flow confirmado', [
                    'pago_id' => $pago->id,
                    'estado' => $estado,
                    'flow_order' => $paymentStatus['flowOrder'] ?? null
                ]);
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Error en confirmación Flow: ' . $e->getMessage());
            return response('Error', 500);
        }
    }

    /**
     * Página de retorno después del pago
     */
    public function return(Request $request)
    {
        try {
            $token = $request->input('token');

            if (!$token) {
                return redirect()
                    ->route('home')
                    ->with('error', 'Token de pago no válido');
            }

            // Buscar el pago
            $pago = Pago::where('token', $token)->first();

            if (!$pago) {
                return redirect()
                    ->route('home')
                    ->with('error', 'Pago no encontrado');
            }

            // Obtener estado actualizado
            $paymentStatus = $this->flowService->getPaymentStatus($token);

            // Actualizar información del pago si es necesario
            if (isset($paymentStatus['status'])) {
                $estado = $this->mapFlowStatus($paymentStatus['status']);

                $pago->update([
                    'estado' => $estado,
                    'response_data' => $paymentStatus
                ]);
            }

            // Redirigir según el estado
            if ($pago->estado === 'aprobado') {
                return view('pagos.success', [
                    'pago' => $pago,
                    'solicitud' => $pago->solicitud,
                    'gateway' => 'Flow'
                ]);
            } else {
                return view('pagos.failed', [
                    'pago' => $pago,
                    'solicitud' => $pago->solicitud,
                    'gateway' => 'Flow'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error en retorno Flow: ' . $e->getMessage());

            return redirect()
                ->route('home')
                ->with('error', 'Error al procesar el resultado del pago');
        }
    }

    /**
     * Mapea los estados de Flow a estados internos
     */
    private function mapFlowStatus($flowStatus)
    {
        $statusMap = [
            1 => 'pendiente',      // Pendiente de pago
            2 => 'aprobado',       // Pagado
            3 => 'rechazado',      // Rechazado
            4 => 'anulado'         // Anulado
        ];

        return $statusMap[$flowStatus] ?? 'pendiente';
    }
}
