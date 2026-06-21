<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use App\Models\Chatbot\Conversacion;
use App\Models\Chatbot\Mensaje;
use App\Models\Chatbot\Uso;
use App\Services\ClaudeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function enviarMensaje(Request $request)
    {
        $request->validate([
            'conversacion_id' => 'required|exists:chatbot_conversaciones,id',
            'mensaje' => 'required|string|max:2000',
        ]);

        $user = Auth::guard('chatbot')->user();
        
        // Verificar límite de mensajes
        if (!$user->puedeEnviarMensaje()) {
            return response()->json([
                'error' => 'Has alcanzado el límite de mensajes diarios. Actualiza tu plan para continuar.'
            ], 429);
        }

        $conversacion = $user->conversaciones()->findOrFail($request->conversacion_id);

        DB::beginTransaction();
        try {
            // Guardar mensaje del usuario
            $mensajeUsuario = Mensaje::create([
                'conversacion_id' => $conversacion->id,
                'contenido' => $request->mensaje,
                'role' => 'user',
            ]);

            // Obtener historial de la conversación para contexto
            $historialMensajes = $conversacion->mensajes()
                ->where('id', '<', $mensajeUsuario->id) // Solo mensajes anteriores
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($msg) {
                    return [
                        'role' => $msg->role,
                        'content' => $msg->contenido
                    ];
                })
                ->toArray();

            // Llamar a Claude AI
            $claudeService = new ClaudeService();
            $respuestaIA = $claudeService->enviarMensaje($historialMensajes, $request->mensaje);

            // Guardar respuesta del bot
            $mensajeBot = Mensaje::create([
                'conversacion_id' => $conversacion->id,
                'contenido' => $respuestaIA['contenido'],
                'role' => 'assistant',
                'metadata' => [
                    'tokens_usados' => $respuestaIA['tokens_usados'],
                    'error' => $respuestaIA['error'],
                    'timestamp' => now()->toIso8601String(),
                ]
            ]);

            // Actualizar estadísticas de uso
            $uso = Uso::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'fecha' => today(),
                ],
                [
                    'mensajes_enviados' => 0,
                    'tokens_usados' => 0,
                ]
            );
            $uso->increment('mensajes_enviados');
            $uso->increment('tokens_usados', $respuestaIA['tokens_usados']);

            // Actualizar timestamp de conversación
            $conversacion->touch();

            DB::commit();

            return response()->json([
                'success' => true,
                'mensaje_usuario' => $mensajeUsuario,
                'mensaje_bot' => $mensajeBot,
                'mensajes_restantes' => $user->getMensajesRestantesHoy(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al enviar mensaje'], 500);
        }
    }

    public function obtenerConversacion($id)
    {
        $user = Auth::guard('chatbot')->user();
        $conversacion = $user->conversaciones()->with('mensajes')->findOrFail($id);
        
        return response()->json([
            'conversacion' => $conversacion,
        ]);
    }

    public function nuevaConversacion(Request $request)
    {
        $request->validate([
            'titulo' => 'nullable|string|max:255',
        ]);

        $user = Auth::guard('chatbot')->user();

        DB::beginTransaction();
        try {
            $conversacion = Conversacion::create([
                'user_id' => $user->id,
                'titulo' => $request->titulo ?? 'Nueva conversación',
                'activa' => true,
            ]);

            // Actualizar estadísticas de uso
            $uso = Uso::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'fecha' => today(),
                ],
                [
                    'mensajes_enviados' => 0,
                    'tokens_usados' => 0,
                ]
            );
            $uso->increment('mensajes_enviados');

            DB::commit();

            return response()->json([
                'success' => true,
                'conversacion' => $conversacion,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear conversación'], 500);
        }
    }
}
