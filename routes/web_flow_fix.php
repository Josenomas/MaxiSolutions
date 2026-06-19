// Debug completo de Flow - PROCESA EL PAGO
Route::any('/pago/flow/debug', function(\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Log::info('=== FLOW DEBUG/CONFIRM ===', [
        'method' => $request->method(),
        'all_params' => $request->all(),
    ]);

    // Intentar procesar el pago
    try {
        $token = $request->input('token');
        if ($token) {
            $pago = \App\Models\Pago::where('token', $token)->first();
            if ($pago && $pago->estado === 'pendiente') {
                $flowService = app(\App\Services\FlowService::class);
                $paymentStatus = $flowService->getPaymentStatus($token);

                if (isset($paymentStatus['status']) && $paymentStatus['status'] == 2) {
                    $pago->update([
                        'estado' => 'aprobado',
                        'referencia_pago' => $paymentStatus['flowOrder'] ?? null,
                        'response_data' => $paymentStatus,
                        'fecha_confirmacion' => now()
                    ]);

                    $pago->solicitud->update(['estado' => 'aceptado']);

                    \Illuminate\Support\Facades\Log::info('✓ Pago confirmado via debug', ['pago_id' => $pago->id]);
                }
            }
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error en debug/confirm: ' . $e->getMessage());
    }

    return response('OK', 200);
});
