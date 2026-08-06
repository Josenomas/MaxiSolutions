<?php

namespace App\Http\Controllers\Admin\Chatbot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\ChatbotConfiguracion;

class ChatbotConfiguracionController extends Controller
{
    public function index()
    {
        if (!auth()->user()->canAccessChatbot()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Configuración global del chatbot almacenada en base de datos
        $config = [
            'limite_gratuito' => ChatbotConfiguracion::obtener('limite_gratuito', 50),
            'limite_basico' => ChatbotConfiguracion::obtener('limite_basico', 500),
            'modelo_default' => ChatbotConfiguracion::obtener('modelo_default', 'claude-sonnet-5'),
            'temperatura_default' => ChatbotConfiguracion::obtener('temperatura_default', 0.7),
            'max_tokens_default' => ChatbotConfiguracion::obtener('max_tokens_default', 500),
            'system_prompt' => ChatbotConfiguracion::obtener('system_prompt', 'Eres un asistente virtual útil y amigable.'),
            'api_key' => ChatbotConfiguracion::obtener('api_key', ''),
        ];

        return view('admin.chatbot.configuracion', compact('config'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Solo super admins pueden modificar la configuración global.');
        }

        $request->validate([
            'limite_gratuito' => 'required|integer|min:1',
            'limite_basico' => 'required|integer|min:1',
            'modelo_default' => 'required|string',
            'temperatura_default' => 'required|numeric|min:0|max:2',
            'max_tokens_default' => 'required|integer|min:1',
            'system_prompt' => 'required|string',
            'api_key' => 'nullable|string',
        ]);

        // Guardar configuración en base de datos
        ChatbotConfiguracion::establecer('limite_gratuito', $request->limite_gratuito);
        ChatbotConfiguracion::establecer('limite_basico', $request->limite_basico);
        ChatbotConfiguracion::establecer('modelo_default', $request->modelo_default);
        ChatbotConfiguracion::establecer('temperatura_default', $request->temperatura_default);
        ChatbotConfiguracion::establecer('max_tokens_default', $request->max_tokens_default);
        ChatbotConfiguracion::establecer('system_prompt', $request->system_prompt);

        // Solo guardar API key si NO es el placeholder de bullets
        if ($request->filled('api_key') && !str_contains($request->api_key, '•')) {
            ChatbotConfiguracion::establecer('api_key', $request->api_key);
        }

        return redirect()->route('admin.chatbot.configuracion')
            ->with('success', 'Configuración actualizada correctamente');
    }
}
