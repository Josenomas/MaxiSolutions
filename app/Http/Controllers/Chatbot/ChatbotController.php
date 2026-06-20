<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function dashboard()
    {
        $user = Auth::guard('chatbot')->user();
        
        $conversaciones = $user->conversaciones()
            ->with('ultimoMensaje')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
        
        $totalConversaciones = $user->conversaciones()->count();
        $mensajesHoy = $user->uso()->whereDate('fecha', today())->sum('mensajes_enviados');
        $mensajesRestantes = $user->getMensajesRestantesHoy();
        
        $stats = [
            'total_conversaciones' => $totalConversaciones,
            'mensajes_hoy' => $mensajesHoy,
            'mensajes_restantes' => $mensajesRestantes,
            'limite_mensajes' => $user->getLimiteMensajes(),
        ];
        
        return view('chatbot.dashboard', compact('user', 'conversaciones', 'stats'));
    }

    public function chat($conversacionId = null)
    {
        $user = Auth::guard('chatbot')->user();
        
        if ($conversacionId) {
            $conversacion = $user->conversaciones()->with('mensajes')->findOrFail($conversacionId);
        } else {
            $conversacion = null;
        }
        
        $conversaciones = $user->conversaciones()
            ->with('ultimoMensaje')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return view('chatbot.chat', compact('user', 'conversacion', 'conversaciones'));
    }
}
