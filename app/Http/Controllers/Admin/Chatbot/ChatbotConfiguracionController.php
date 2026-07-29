<?php

namespace App\Http\Controllers\Admin\Chatbot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ChatbotConfiguracionController extends Controller
{
    public function index()
    {
        if (!auth()->user()->canAccessChatbot()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Configuración global del chatbot almacenada en cache
        $config = [
            'limite_gratuito' => Cache::get('chatbot.limite_gratuito', 50),
            'limite_basico' => Cache::get('chatbot.limite_basico', 500),
            'modelo_default' => Cache::get('chatbot.modelo_default', 'gpt-3.5-turbo'),
            'temperatura_default' => Cache::get('chatbot.temperatura_default', 0.7),
            'max_tokens_default' => Cache::get('chatbot.max_tokens_default', 500),
            'system_prompt' => Cache::get('chatbot.system_prompt', 'Eres un asistente virtual útil y amigable.'),
            'api_key' => Cache::get('chatbot.api_key', ''),
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

        // Guardar configuración en cache (permanente)
        Cache::forever('chatbot.limite_gratuito', $request->limite_gratuito);
        Cache::forever('chatbot.limite_basico', $request->limite_basico);
        Cache::forever('chatbot.modelo_default', $request->modelo_default);
        Cache::forever('chatbot.temperatura_default', $request->temperatura_default);
        Cache::forever('chatbot.max_tokens_default', $request->max_tokens_default);
        Cache::forever('chatbot.system_prompt', $request->system_prompt);

        if ($request->filled('api_key')) {
            Cache::forever('chatbot.api_key', $request->api_key);
        }

        return redirect()->route('admin.chatbot.configuracion')
            ->with('success', 'Configuración actualizada correctamente');
    }
}
