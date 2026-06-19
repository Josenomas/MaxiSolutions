<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BoletaController extends Controller
{
    /**
     * Generar boleta electrónica en PDF (requiere autenticación)
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

    /**
     * Generar boleta pública con hash de seguridad (sin autenticación)
     */
    public function generarBoletaPublica(Request $request, $pagoId)
    {
        $pago = Pago::findOrFail($pagoId);

        // Verificar que el pago esté confirmado
        if ($pago->estado !== 'aprobado' && $pago->estado !== 'completado') {
            abort(403, 'Este pago aún no ha sido confirmado');
        }

        // Verificar hash de seguridad
        $hash = $request->query('hash');
        $expectedHash = md5($pago->id . $pago->created_at);

        if ($hash !== $expectedHash) {
            abort(403, 'Enlace de boleta inválido o expirado');
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
