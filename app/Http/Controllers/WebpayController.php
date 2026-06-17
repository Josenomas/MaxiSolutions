<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Solicitud;
use App\Models\SecurityLog;
use App\Services\WebpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PagoRecibidoMail;

class WebpayController extends Controller
{
    protected $webpayService;
    
    public function __construct(WebpayService $webpayService)
    {
        $this->webpayService = $webpayService;
    }
    
    /**
     * Mostrar página de checkout
     */
    public function checkout($solicitudId)
    {
        $solicitud = Solicitud::with('servicio')->findOrFail($solicitudId);

        // Verificar que la solicitud tenga un monto cotizado
        if (!$solicitud->monto_cotizado) {
            return redirect()->back()->with('error', 'Esta solicitud no tiene un monto cotizado asignado.');
        }

        return view('pagos.checkout', compact('solicitud'));
    }
    
    /**
     * Iniciar pago con Webpay
     */
    public function pay(Request $request, $solicitudId)
    {
        $solicitud = Solicitud::findOrFail($solicitudId);
        
        $validated = $request->validate([
            'monto' => 'required|numeric|min:50'
        ]);
        
        // Generar orden de compra única
        $buyOrder = 'ORD-' . $solicitud->id . '-' . time();
        $sessionId = auth()->check() ? auth()->id() : 'guest-' . Str::random(10);
        $amount = (int) $validated['monto']; // Monto en pesos chilenos
        $returnUrl = route('webpay.return');
        
        // Crear registro de pago pendiente
        $pago = Pago::create([
            'solicitud_id' => $solicitud->id,
            'usuario_id' => auth()->id(),
            'monto' => $amount,
            'metodo_pago' => 'webpay',
            'estado' => 'pendiente',
            'buy_order' => $buyOrder
        ]);
        
        // Log intento de pago
        SecurityLog::logPaymentAttempt(auth()->id(), $solicitud->id, 'webpay', $amount);
        
        // Crear transacción en Webpay
        $result = $this->webpayService->createTransaction(
            $buyOrder,
            $sessionId,
            $amount,
            $returnUrl
        );
        
        if (!$result['success']) {
            $pago->update(['estado' => 'fallido']);
            return redirect()->back()->with('error', 'Error al iniciar el pago: ' . $result['error']);
        }
        
        // Guardar token
        $pago->update(['token' => $result['token']]);
        
        // Redirigir a Webpay
        return view('pagos.redirect-webpay', [
            'url' => $result['url'],
            'token' => $result['token']
        ]);
    }
    
    /**
     * Callback de retorno desde Webpay
     */
    public function return(Request $request)
    {
        $token = $request->get('token_ws');
        
        if (!$token) {
            return redirect()->route('home')->with('error', 'Pago cancelado o inválido.');
        }
        
        // Confirmar transacción
        $result = $this->webpayService->commit($token);
        
        // Buscar pago por token
        $pago = Pago::where('token', $token)->first();
        
        if (!$pago) {
            return redirect()->route('home')->with('error', 'Pago no encontrado.');
        }
        
        if ($result['success']) {
            $response = $result['response'];
            
            $pago->update([
                'estado' => 'completado',
                'referencia_pago' => $response->getBuyOrder(),
                'response_data' => [
                    'authorization_code' => $response->getAuthorizationCode(),
                    'payment_type_code' => $response->getPaymentTypeCode(),
                    'response_code' => $response->getResponseCode(),
                    'amount' => $response->getAmount(),
                    'card_number' => $response->getCardDetail() ? $response->getCardDetail()['card_number'] : null
                ],
                'fecha_confirmacion' => now()
            ]);
            
            // Actualizar estado de solicitud
            $pago->solicitud->update(['estado' => 'aceptado']);
            
            // Enviar notificación por email
            if ($pago->solicitud->usuario) {
                Mail::to($pago->solicitud->usuario->email)->send(new PagoRecibidoMail($pago));
            }
            
            return view('pagos.success', compact('pago'));
        } else {
            $pago->update(['estado' => 'fallido']);
            
            return view('pagos.failed', [
                'error' => $result['error'] ?? 'Pago rechazado'
            ]);
        }
    }
}
