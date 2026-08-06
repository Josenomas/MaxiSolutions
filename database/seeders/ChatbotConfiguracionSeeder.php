<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatbotConfiguracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configuraciones = [
            ['clave' => 'limite_gratuito', 'valor' => '50'],
            ['clave' => 'limite_basico', 'valor' => '500'],
            ['clave' => 'modelo_default', 'valor' => 'claude-sonnet-5'],
            ['clave' => 'temperatura_default', 'valor' => '0.7'],
            ['clave' => 'max_tokens_default', 'valor' => '500'],
            ['clave' => 'system_prompt', 'valor' => 'Eres un asistente virtual útil y amigable.'],
            ['clave' => 'api_key', 'valor' => env('ANTHROPIC_API_KEY', '')],
        ];

        foreach ($configuraciones as $config) {
            \App\Models\ChatbotConfiguracion::updateOrCreate(
                ['clave' => $config['clave']],
                [
                    'valor' => $config['valor'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
