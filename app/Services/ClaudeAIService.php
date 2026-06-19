<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaudeAIService
{
    private $apiKey;
    private $apiUrl = 'https://api.anthropic.com/v1/messages';
    private $model = 'claude-3-5-sonnet-20241022';
    private $maxTokens = 4096;

    public function __construct()
    {
        $this->apiKey = config('services.claude.api_key');
    }

    /**
     * Generar pregunta PAES con IA
     */
    public function generarPregunta($materia, $tema, $dificultad = 'medio')
    {
        $prompt = $this->construirPromptPregunta($materia, $tema, $dificultad);

        $response = $this->enviarMensaje($prompt);

        return $this->parsearPregunta($response);
    }

    /**
     * Explicar respuesta incorrecta
     */
    public function explicarError($pregunta, $respuestaUsuario, $respuestaCorrecta, $contexto = null)
    {
        $prompt = "Eres un tutor educativo experto en preparación PAES.\n\n";
        $prompt .= "Pregunta: {$pregunta}\n\n";

        if ($contexto) {
            $prompt .= "Contexto: {$contexto}\n\n";
        }

        $prompt .= "El estudiante eligió: {$respuestaUsuario}\n";
        $prompt .= "La respuesta correcta es: {$respuestaCorrecta}\n\n";
        $prompt .= "Explica de forma clara y pedagógica:\n";
        $prompt .= "1. Por qué la respuesta del estudiante es incorrecta\n";
        $prompt .= "2. Por qué la respuesta correcta es la adecuada\n";
        $prompt .= "3. Conceptos clave que debe repasar\n\n";
        $prompt .= "Usa un lenguaje amigable y ejemplos si es necesario.";

        return $this->enviarMensaje($prompt);
    }

    /**
     * Analizar desempeño del estudiante
     */
    public function analizarDesempeno($estadisticas, $materia, $tema = null)
    {
        $prompt = "Eres un experto en análisis educativo y preparación PAES.\n\n";
        $prompt .= "Analiza el siguiente desempeño del estudiante en {$materia}";

        if ($tema) {
            $prompt .= " - {$tema}";
        }

        $prompt .= ":\n\n";
        $prompt .= json_encode($estadisticas, JSON_PRETTY_PRINT);
        $prompt .= "\n\nGenera un análisis detallado en formato JSON con:\n";
        $prompt .= "{\n";
        $prompt .= '  "nivel_dominio": 0-100,'."\n";
        $prompt .= '  "fortalezas": ["punto 1", "punto 2"],'."\n";
        $prompt .= '  "debilidades": ["punto 1", "punto 2"],'."\n";
        $prompt .= '  "recomendaciones": ["acción 1", "acción 2", "acción 3"]'."\n";
        $prompt .= "}";

        $response = $this->enviarMensaje($prompt);

        return json_decode($response, true);
    }

    /**
     * Responder pregunta del tutor
     */
    public function responderDuda($preguntaUsuario, $contexto = null)
    {
        $prompt = "Eres un tutor educativo experto en preparación PAES.\n\n";

        if ($contexto) {
            $prompt .= "Contexto: {$contexto}\n\n";
        }

        $prompt .= "Pregunta del estudiante: {$preguntaUsuario}\n\n";
        $prompt .= "Responde de forma clara, concisa y pedagógica. Usa ejemplos si es necesario.";

        return $this->enviarMensaje($prompt);
    }

    /**
     * Generar múltiples preguntas
     */
    public function generarPreguntas($materia, $tema, $cantidad = 5, $dificultad = 'medio')
    {
        $prompt = $this->construirPromptPreguntas($materia, $tema, $cantidad, $dificultad);

        $response = $this->enviarMensaje($prompt);

        return $this->parsearPreguntas($response);
    }

    /**
     * Construir prompt para generar una pregunta
     */
    private function construirPromptPregunta($materia, $tema, $dificultad)
    {
        $nivelDificultad = [
            'facil' => 'básico, similar a las primeras preguntas del examen',
            'medio' => 'intermedio, requiere análisis y aplicación de conceptos',
            'dificil' => 'avanzado, requiere razonamiento complejo y síntesis'
        ];

        $prompt = "Eres un experto en crear preguntas de selección múltiple para la Prueba de Acceso a la Educación Superior (PAES) de Chile.\n\n";
        $prompt .= "Genera UNA pregunta de {$materia} sobre el tema: {$tema}\n";
        $prompt .= "Nivel de dificultad: {$nivelDificultad[$dificultad]}\n\n";
        $prompt .= "Formato requerido (JSON):\n";
        $prompt .= "{\n";
        $prompt .= '  "enunciado": "Texto de la pregunta",'."\n";
        $prompt .= '  "contexto": "Texto adicional o null",'."\n";
        $prompt .= '  "alternativas": ['."\n";
        $prompt .= '    {"letra": "A", "texto": "Opción A", "es_correcta": false, "explicacion": "Por qué es incorrecta"},'."\n";
        $prompt .= '    {"letra": "B", "texto": "Opción B", "es_correcta": true, "explicacion": "Por qué es correcta"},'."\n";
        $prompt .= '    {"letra": "C", "texto": "Opción C", "es_correcta": false, "explicacion": "Por qué es incorrecta"},'."\n";
        $prompt .= '    {"letra": "D", "texto": "Opción D", "es_correcta": false, "explicacion": "Por qué es incorrecta"}'."\n";
        $prompt .= '  ],'."\n";
        $prompt .= '  "explicacion": "Explicación detallada de la respuesta correcta"'."\n";
        $prompt .= "}\n\n";
        $prompt .= "IMPORTANTE:\n";
        $prompt .= "- La pregunta debe ser clara y precisa\n";
        $prompt .= "- Los distractores deben ser plausibles\n";
        $prompt .= "- Respeta el formato PAES oficial\n";
        $prompt .= "- Solo UNA alternativa debe ser correcta\n";
        $prompt .= "- Responde SOLO con el JSON, sin texto adicional";

        return $prompt;
    }

    /**
     * Construir prompt para generar múltiples preguntas
     */
    private function construirPromptPreguntas($materia, $tema, $cantidad, $dificultad)
    {
        $prompt = $this->construirPromptPregunta($materia, $tema, $dificultad);
        $prompt = str_replace('UNA pregunta', "{$cantidad} preguntas diferentes", $prompt);
        $prompt = str_replace('"enunciado"', '[\n    {\n      "enunciado"', $prompt);
        $prompt .= "\n\nGenera un array JSON con {$cantidad} preguntas.";

        return $prompt;
    }

    /**
     * Enviar mensaje a Claude API
     */
    private function enviarMensaje($prompt, $systemPrompt = null)
    {
        try {
            $messages = [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ];

            $body = [
                'model' => $this->model,
                'max_tokens' => $this->maxTokens,
                'messages' => $messages,
            ];

            if ($systemPrompt) {
                $body['system'] = $systemPrompt;
            }

            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->post($this->apiUrl, $body);

            if (!$response->successful()) {
                Log::error('Claude API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Error al comunicarse con Claude API: ' . $response->body());
            }

            $data = $response->json();

            // Registrar uso de tokens
            $this->registrarUsoTokens(
                $data['usage']['input_tokens'] ?? 0,
                $data['usage']['output_tokens'] ?? 0
            );

            return $data['content'][0]['text'] ?? '';

        } catch (\Exception $e) {
            Log::error('Claude AI Service Error', [
                'message' => $e->getMessage(),
                'prompt' => substr($prompt, 0, 200)
            ]);

            throw $e;
        }
    }

    /**
     * Parsear respuesta de pregunta
     */
    private function parsearPregunta($respuesta)
    {
        // Limpiar posible markdown
        $respuesta = trim($respuesta);
        $respuesta = preg_replace('/```json\n?/', '', $respuesta);
        $respuesta = preg_replace('/```\n?/', '', $respuesta);

        $data = json_decode($respuesta, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Error parseando respuesta de Claude', [
                'respuesta' => $respuesta,
                'error' => json_last_error_msg()
            ]);
            throw new \Exception('Error al parsear respuesta de IA');
        }

        return $data;
    }

    /**
     * Parsear múltiples preguntas
     */
    private function parsearPreguntas($respuesta)
    {
        $data = $this->parsearPregunta($respuesta);

        // Si ya es un array, retornar
        if (isset($data[0])) {
            return $data;
        }

        // Si es un objeto único, envolverlo en array
        return [$data];
    }

    /**
     * Registrar uso de tokens (para billing)
     */
    private function registrarUsoTokens($inputTokens, $outputTokens)
    {
        if (!auth()->check()) {
            return;
        }

        $totalTokens = $inputTokens + $outputTokens;

        // Registrar en tabla de uso
        if (auth()->user()->organizacion_id) {
            $producto = \App\Models\Producto::where('slug', 'paes')->first();

            if ($producto) {
                \App\Models\UsoProducto::create([
                    'organizacion_id' => auth()->user()->organizacion_id,
                    'producto_id' => $producto->id,
                    'user_id' => auth()->id(),
                    'metrica' => 'tokens_ia',
                    'cantidad' => $totalTokens,
                    'fecha' => today(),
                ]);
            }
        }

        Log::info('Uso de Claude API', [
            'user_id' => auth()->id(),
            'input_tokens' => $inputTokens,
            'output_tokens' => $outputTokens,
            'total' => $totalTokens
        ]);
    }

    /**
     * Verificar disponibilidad del servicio
     */
    public function verificarConexion()
    {
        try {
            $this->enviarMensaje('Test');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
