<?php

namespace App\Http\Controllers\Admin\Chatbot;

use App\Http\Controllers\Controller;
use App\Models\Chatbot\ChatbotUser;
use Illuminate\Http\Request;

class ChatbotUsuariosController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->canAccessChatbot()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $query = ChatbotUser::query();

        // Filtros
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo === '1');
        }

        $usuarios = $query->withCount('conversaciones')
            ->latest()
            ->paginate(20);

        return view('admin.chatbot.usuarios.index', compact('usuarios'));
    }

    public function show(ChatbotUser $usuario)
    {
        if (!auth()->user()->canAccessChatbot()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $usuario->load(['conversaciones.mensajes', 'uso']);

        $stats = [
            'total_conversaciones' => $usuario->conversaciones()->count(),
            'total_mensajes' => $usuario->conversaciones()->withCount('mensajes')->get()->sum('mensajes_count'),
            'mensajes_hoy' => $usuario->uso()->whereDate('fecha', today())->sum('mensajes_enviados'),
            'tokens_usados' => $usuario->uso()->sum('tokens_usados'),
        ];

        return view('admin.chatbot.usuarios.show', compact('usuario', 'stats'));
    }

    public function update(Request $request, ChatbotUser $usuario)
    {
        if (!auth()->user()->canAccessChatbot()) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $request->validate([
            'plan' => 'required|in:gratuito,basico,premium',
            'activo' => 'required|boolean',
        ]);

        $usuario->update([
            'plan' => $request->plan,
            'activo' => $request->activo,
        ]);

        return redirect()->route('admin.chatbot.usuarios.show', $usuario)
            ->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(ChatbotUser $usuario)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Solo super admins pueden eliminar usuarios.');
        }

        $usuario->delete();

        return redirect()->route('admin.chatbot.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente');
    }
}
