<?php

namespace App\Http\Controllers\Admin\Chatbot;

use App\Http\Controllers\Controller;
use App\Models\Chatbot\ChatbotUser;
use App\Models\Chatbot\Conversacion;
use App\Models\Chatbot\Mensaje;
use App\Models\Chatbot\Uso;
use Illuminate\Support\Facades\DB;

class ChatbotDashboardController extends Controller
{
    public function index()
    {
        // Verificar permisos
        if (!auth()->user()->canAccessChatbot()) {
            abort(403, 'No tienes permisos para acceder al panel de Chatbot.');
        }

        // Estadísticas generales
        $stats = [
            'total_usuarios' => ChatbotUser::count(),
            'usuarios_activos' => ChatbotUser::where('activo', true)->count(),
            'total_conversaciones' => Conversacion::count(),
            'total_mensajes' => Mensaje::count(),
            'mensajes_hoy' => Mensaje::whereDate('created_at', today())->count(),
            'usuarios_gratuitos' => ChatbotUser::where('plan', 'gratuito')->count(),
            'usuarios_basicos' => ChatbotUser::where('plan', 'basico')->count(),
            'usuarios_premium' => ChatbotUser::where('plan', 'premium')->count(),
        ];

        // Usuarios recientes
        $usuarios_recientes = ChatbotUser::latest()
            ->take(10)
            ->get();

        // Conversaciones recientes
        $conversaciones_recientes = Conversacion::with(['user', 'mensajes'])
            ->latest()
            ->take(10)
            ->get();

        // Uso por día (últimos 7 días)
        $usoPorDia = Uso::select(
            DB::raw('DATE(fecha) as dia'),
            DB::raw('SUM(mensajes_enviados) as total_mensajes')
        )
        ->where('fecha', '>=', now()->subDays(7))
        ->groupBy('dia')
        ->orderBy('dia')
        ->get();

        // Distribución de planes
        $distribucionPlanes = ChatbotUser::select('plan', DB::raw('COUNT(*) as total'))
            ->groupBy('plan')
            ->get();

        // Top usuarios por mensajes
        $topUsuarios = ChatbotUser::withCount('conversaciones')
            ->orderBy('conversaciones_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.chatbot.dashboard', compact(
            'stats',
            'usuarios_recientes',
            'conversaciones_recientes',
            'usoPorDia',
            'distribucionPlanes',
            'topUsuarios'
        ));
    }
}
