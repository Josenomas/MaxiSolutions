<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\Solicitud;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Estadísticas generales del dominio principal
        $stats = [
            'total_servicios' => Servicio::count(),
            'total_solicitudes' => Solicitud::count(),
            'solicitudes_pendientes' => Solicitud::where('estado', 'pendiente')->count(),
            'total_usuarios' => User::where('tipo_usuario', 'cliente')->count(),
            'total_pagos' => Pago::where('estado', 'completado')->sum('monto'),
            'pagos_mes' => Pago::where('estado', 'completado')
                ->whereMonth('created_at', date('m'))
                ->sum('monto'),
        ];

        // Estadísticas de subdominios (si tiene permisos)
        $chatbotStats = null;
        if ($user->canAccessChatbot()) {
            $chatbotStats = [
                'total_usuarios' => \App\Models\Chatbot\ChatbotUser::count(),
                'total_conversaciones' => \App\Models\Chatbot\Conversacion::count(),
                'mensajes_hoy' => \App\Models\Chatbot\Mensaje::whereDate('created_at', today())->count(),
            ];
        }

        $paesStats = null;
        if ($user->canAccessPaes()) {
            // TODO: Agregar stats PAES cuando estén implementados
            $paesStats = [];
        }

        // Solicitudes recientes
        $solicitudes_recientes = Solicitud::with('servicio')
            ->latest()
            ->take(5)
            ->get();

        // Gráfico: Solicitudes por mes (últimos 6 meses)
        $solicitudesPorMes = Solicitud::select(
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

        // Gráfico: Solicitudes por estado
        $solicitudesPorEstado = Solicitud::select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->get();

        // Gráfico: Ingresos por mes (últimos 6 meses)
        $ingresosPorMes = Pago::select(
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('SUM(monto) as total')
        )
        ->where('estado', 'completado')
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

        // Gráfico: Servicios más solicitados
        $serviciosMasSolicitados = Servicio::withCount('solicitudes')
            ->orderBy('solicitudes_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'solicitudes_recientes',
            'solicitudesPorMes',
            'solicitudesPorEstado',
            'ingresosPorMes',
            'serviciosMasSolicitados',
            'chatbotStats',
            'paesStats'
        ));
    }
}