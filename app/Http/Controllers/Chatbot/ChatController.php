<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use App\Models\Chatbot\Conversacion;
use App\Models\Chatbot\Mensaje;
use App\Models\Chatbot\Uso;
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

            // Simular respuesta del chatbot (SIN IA real)
            $respuestaBotContenido = "Gracias por tu mensaje. Actualmente soy una versión básica sin IA real implementada. Pronto tendré inteligencia artificial completa para ayudarte mejor.";

            $mensajeBot = Mensaje::create([
                'conversacion_id' => $conversacion->id,
                'contenido' => $respuestaBotContenido,
                'role' => 'assistant',
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
