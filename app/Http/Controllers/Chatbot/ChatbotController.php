<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function dashboard()
    {
        $user = Auth::guard('chatbot')->user();

        $conversacionesRecientes = $user->conversaciones()
            ->with('ultimoMensaje')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        $totalConversaciones = $user->conversaciones()->count();
        $roastsDisponibles = $user->getMensajesRestantesHoy();

        return view('chatbot.dashboard', compact('user', 'conversacionesRecientes', 'totalConversaciones', 'roastsDisponibles'));
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
