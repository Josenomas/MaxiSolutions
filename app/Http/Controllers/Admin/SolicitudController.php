<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Models\SolicitudComentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CambioEstadoMail;

class SolicitudController extends Controller
{
    public function index()
    {
        $solicitudes = Solicitud::with(['servicio', 'usuario'])
            ->latest()
            ->paginate(15);

        return view('admin.solicitudes.index', compact('solicitudes'));
    }

    public function show(Solicitud $solicitud)
    {
        $solicitud->load(['servicio', 'usuario', 'pagos', 'comentarios.user', 'historial.user']);

        // Verificar si el cliente tiene cuenta registrada
        $clienteRegistrado = \App\Models\User::where('email', $solicitud->email_cliente)->first();

        return view('admin.solicitudes.show', compact('solicitud', 'clienteRegistrado'));
    }

    public function update(Request $request, Solicitud $solicitud)
    {
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,en_revision,cotizada,aceptada,en_desarrollo,completada,cancelada',
            'notas_admin' => 'nullable|string',
            'monto_cotizado' => 'nullable|numeric|min:0',
            'fecha_estimada_entrega' => 'nullable|date',
            'motivo_cancelacion' => 'nullable|string'
        ]);

        // Capturar estado anterior para la notificación
        $estadoAnterior = $solicitud->estado;
        
        $solicitud->update($validated);

        // Enviar notificación por email si el estado cambió y el usuario existe
        if ($estadoAnterior !== $validated['estado'] && $solicitud->usuario) {
            Mail::to($solicitud->usuario->email)->send(
                new CambioEstadoMail($solicitud, $estadoAnterior, $validated['estado'])
            );
        }

        return redirect()->route('admin.solicitudes.show', $solicitud)
            ->with('success', 'Solicitud actualizada exitosamente.');
    }

    public function storeComment(Request $request, Solicitud $solicitud)
    {
        $validated = $request->validate([
            'comentario' => 'required|string|max:1000',
            'es_interno' => 'boolean'
        ]);

        $solicitud->comentarios()->create([
            'user_id' => Auth::id(),
            'comentario' => $validated['comentario'],
            'es_interno' => $request->has('es_interno')
        ]);

        return redirect()->route('admin.solicitudes.show', $solicitud)
            ->with('success', 'Comentario agregado exitosamente.');
    }

    public function destroy(Solicitud $solicitud)
    {
        $solicitud->delete();

        return redirect()->route('admin.solicitudes.index')
            ->with('success', 'Solicitud eliminada exitosamente.');
    }
}
