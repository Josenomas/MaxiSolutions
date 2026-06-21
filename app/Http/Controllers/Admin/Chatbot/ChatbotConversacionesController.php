<?php

namespace App\Http\Controllers\Admin\Chatbot;

use App\Http\Controllers\Controller;
use App\Models\Chatbot\Conversacion;
use Illuminate\Http\Request;

class ChatbotConversacionesController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->canAccessChatbot()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $query = Conversacion::with(['user', 'mensajes']);

        // Filtros
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('titulo', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('activa')) {
            $query->where('activa', $request->activa === '1');
        }

        $conversaciones = $query->withCount('mensajes')
            ->latest()
            ->paginate(20);

        return view('admin.chatbot.conversaciones.index', compact('conversaciones'));
    }

    public function show(Conversacion $conversacion)
    {
        if (!auth()->user()->canAccessChatbot()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $conversacion->load(['user', 'mensajes']);

        return view('admin.chatbot.conversaciones.show', compact('conversacion'));
    }

    public function destroy(Conversacion $conversacion)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Solo super admins pueden eliminar conversaciones.');
        }

        $conversacion->delete();

        return redirect()->route('admin.chatbot.conversaciones.index')
            ->with('success', 'Conversación eliminada correctamente');
    }
}
