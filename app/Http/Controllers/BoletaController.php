<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BoletaController extends Controller
{
    /**
     * Generar boleta electrónica en PDF
     */
    public function generarBoleta(Pago $pago)
    {
        // Verificar que el pago esté confirmado
        if ($pago->estado !== 'aprobado' && $pago->estado !== 'completado') {
            abort(403, 'Este pago aún no ha sido confirmado');
        }

        // Verificar ownership: debe ser el dueño del pago o admin
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Debes iniciar sesión para descargar la boleta');
        }

        if ($pago->usuario_id !== $user->id && $user->tipo_usuario !== 'admin') {
            abort(403, 'No tienes permiso para ver esta boleta');
        }

        // Cargar relaciones necesarias
        $pago->load(['solicitud.servicio', 'solicitud.usuario']);

        // Generar PDF
        $pdf = Pdf::loadView('pagos.boleta-pdf', compact('pago'));

        // Nombre del archivo
        $filename = 'Boleta-' . str_pad($pago->id, 8, '0', STR_PAD_LEFT) . '.pdf';

        // Descargar PDF
        return $pdf->download($filename);
    }
}
