<?php

namespace App\Console\Commands;

use App\Models\Pago;
use App\Services\FlowService;
use Illuminate\Console\Command;

class SyncFlowPayment extends Command
{
    protected $signature = 'flow:sync {pago_id}';
    protected $description = 'Sincroniza el estado de un pago con Flow';

    private $flowService;

    public function __construct(FlowService $flowService)
    {
        parent::__construct();
        $this->flowService = $flowService;
    }

    public function handle()
    {
        $pagoId = $this->argument('pago_id');
        $pago = Pago::findOrFail($pagoId);

        $this->info("Consultando estado del pago #{$pagoId} con token: {$pago->token}");

        try {
            $paymentStatus = $this->flowService->getPaymentStatus($pago->token);

            $this->info("Respuesta de Flow:");
            $this->line(json_encode($paymentStatus, JSON_PRETTY_PRINT));

            if (isset($paymentStatus['status'])) {
                $statusMap = [
                    1 => 'pendiente',
                    2 => 'aprobado',
                    3 => 'rechazado',
                    4 => 'anulado'
                ];

                $estado = $statusMap[$paymentStatus['status']] ?? 'pendiente';

                $pago->update([
                    'estado' => $estado,
                    'referencia_pago' => $paymentStatus['flowOrder'] ?? null,
                    'response_data' => $paymentStatus,
                    'fecha_confirmacion' => now()
                ]);

                if ($estado === 'aprobado') {
                    $pago->solicitud->update(['estado' => 'aceptado']);
                    $this->info("✓ Pago actualizado a: APROBADO");
                    $this->info("✓ Solicitud actualizada a: ACEPTADO");
                } else {
                    $this->info("✓ Pago actualizado a: " . strtoupper($estado));
                }
            }
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
