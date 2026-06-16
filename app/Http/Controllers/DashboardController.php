<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\SolicitudComentario;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Obtener solicitudes del usuario por su email
        $solicitudes = Solicitud::where('email_cliente', $user->email)
            ->with(['servicio', 'pagos', 'historial'])
            ->latest()
            ->get();

        // Estadísticas del cliente
        $stats = [
            'total_solicitudes' => $solicitudes->count(),
            'solicitudes_pendientes' => $solicitudes->whereIn('estado', ['pendiente', 'en_revision'])->count(),
            'solicitudes_en_proceso' => $solicitudes->whereIn('estado', ['cotizada', 'aceptada', 'en_desarrollo'])->count(),
            'solicitudes_completadas' => $solicitudes->where('estado', 'completada')->count(),
            'solicitudes_canceladas' => $solicitudes->where('estado', 'cancelada')->count(),
            'total_pagado' => Pago::whereIn('solicitud_id', $solicitudes->pluck('id'))
                ->where('estado', 'completado')
                ->sum('monto'),
            'pagos_pendientes' => Pago::whereIn('solicitud_id', $solicitudes->pluck('id'))
                ->where('estado', 'pendiente')
                ->sum('monto'),
        ];

        return view('dashboard', compact('solicitudes', 'stats'));
    }

    public function showSolicitud(Solicitud $solicitud)
    {
        // Verificar que la solicitud pertenece al usuario
        if ($solicitud->email_cliente !== Auth::user()->email) {
            abort(403, 'No tienes permiso para ver esta solicitud');
        }

        // Cargar relaciones necesarias (solo comentarios públicos)
        $solicitud->load([
            'servicio',
            'pagos',
            'historial.user',
            'comentarios' => function($query) {
                $query->where('es_interno', false)->with('user');
            }
        ]);

        return view('solicitud-detalle', compact('solicitud'));
    }

    public function storeComentario(Request $request, Solicitud $solicitud)
    {
        // Verificar que la solicitud pertenece al usuario
        if ($solicitud->email_cliente !== Auth::user()->email) {
            abort(403, 'No tienes permiso para comentar en esta solicitud');
        }

        $validated = $request->validate([
            'comentario' => 'required|string|max:1000'
        ]);

        $solicitud->comentarios()->create([
            'user_id' => Auth::id(),
            'comentario' => $validated['comentario'],
            'es_interno' => false // Los clientes solo pueden hacer comentarios públicos
        ]);

        return redirect()->route('solicitud.detalle', $solicitud)
            ->with('success', 'Comentario agregado exitosamente');
    }
}