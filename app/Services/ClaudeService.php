<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClaudeService
{
    private $apiKey;
    private $apiUrl = 'https://api.anthropic.com/v1/messages';

    public function __construct()
    {
        // Primero intentar desde cache, luego desde .env
        $this->apiKey = Cache::get('chatbot.api_key') ?? env('ANTHROPIC_API_KEY');
    }

    /**
     * Enviar mensaje a Claude AI y obtener respuesta
     *
     * @param array $conversacionHistorial Array de mensajes previos [['role' => 'user/assistant', 'content' => '...']]
     * @param string $mensajeNuevo El nuevo mensaje del usuario
     * @return array ['contenido' => string, 'tokens_usados' => int, 'error' => string|null]
     */
    public function enviarMensaje(array $conversacionHistorial, string $mensajeNuevo): array
    {
        // Verificar que hay API key configurada
        if (empty($this->apiKey)) {
            return [
                'contenido' => 'Error: No hay API key configurada. Por favor, contacta al administrador.',
                'tokens_usados' => 0,
                'error' => 'No API key configured'
            ];
        }

        // Leer configuración del admin
        $modelo = Cache::get('chatbot.modelo_default', 'claude-sonnet-5');
        $temperatura = (float) Cache::get('chatbot.temperatura_default', 0.7);
        $maxTokens = (int) Cache::get('chatbot.max_tokens_default', 4096);
        $systemPrompt = Cache::get('chatbot.system_prompt', 'Eres un asistente virtual útil y amigable.');

        // Construir array de mensajes
        $messages = [];

        // Agregar mensajes anteriores de la conversación
        foreach ($conversacionHistorial as $msg) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['content']
            ];
        }

        // Agregar el nuevo mensaje del usuario
        $messages[] = [
            'role' => 'user',
            'content' => $mensajeNuevo
        ];

        try {
            // Llamar a la API de Claude con retry en caso de sobrecarga (529)
            $maxRetries = 3;
            $retryDelay = 2; // segundos
            $attempt = 0;

            do {
                $response = Http::withHeaders([
                    'x-api-key' => $this->apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type' => 'application/json',
                ])->timeout(30)->post($this->apiUrl, [
                    'model' => $modelo,
                    'max_tokens' => $maxTokens,
                    'system' => $systemPrompt,
                    'messages' => $messages,
                ]);

                $attempt++;

                // Si es 529 (Overloaded) y quedan intentos, esperar y reintentar
                if ($response->status() === 529 && $attempt < $maxRetries) {
                    sleep($retryDelay);
                    continue;
                }

                break;
            } while ($attempt < $maxRetries);

            if ($response->successful()) {
                $data = $response->json();

                // Extraer respuesta y tokens usados
                // Buscar el primer elemento de tipo "text" en el array content
                $contenido = 'Error: Respuesta vacía de la API';
                if (isset($data['content']) && is_array($data['content'])) {
                    foreach ($data['content'] as $item) {
                        if (isset($item['type']) && $item['type'] === 'text' && isset($item['text'])) {
                            $contenido = $item['text'];
                            break;
                        }
                    }
                }

                $tokensUsados = ($data['usage']['input_tokens'] ?? 0) + ($data['usage']['output_tokens'] ?? 0);

                return [
                    'contenido' => $contenido,
                    'tokens_usados' => $tokensUsados,
                    'error' => null
                ];
            } else {
                // Error de la API
                $errorMsg = $response->json()['error']['message'] ?? 'Error desconocido';

                Log::error('Claude API Error', [
                    'status' => $response->status(),
                    'error' => $errorMsg,
                    'response' => $response->body()
                ]);

                return [
                    'contenido' => 'Lo siento, hubo un error al procesar tu mensaje. Por favor, intenta de nuevo.',
                    'tokens_usados' => 0,
                    'error' => $errorMsg
                ];
            }
        } catch (\Exception $e) {
            Log::error('Claude API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'contenido' => 'Lo siento, no pude procesar tu mensaje en este momento. Por favor, intenta más tarde.',
                'tokens_usados' => 0,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verificar si la API key está configurada
     */
    public function tieneApiKey(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Probar conexión con la API
     */
    public function probarConexion(): array
    {
        if (!$this->tieneApiKey()) {
            return [
                'success' => false,
                'message' => 'No hay API key configurada'
            ];
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(10)->post($this->apiUrl, [
                'model' => 'claude-sonnet-5',
                'max_tokens' => 10,
                'messages' => [
                    ['role' => 'user', 'content' => 'Hola']
                ],
            ]);

            return [
                'success' => $response->successful(),
                'message' => $response->successful() ? 'Conexión exitosa' : 'Error: ' . $response->status()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}
