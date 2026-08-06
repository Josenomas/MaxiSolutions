<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatbot_configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique(); // nombre de la configuración
            $table->text('valor')->nullable(); // valor de la configuración
            $table->timestamps();
        });

        // Insertar valores por defecto
        DB::table('chatbot_configuraciones')->insert([
            ['clave' => 'limite_gratuito', 'valor' => '50', 'created_at' => now(), 'updated_at' => now()],
            ['clave' => 'limite_basico', 'valor' => '500', 'created_at' => now(), 'updated_at' => now()],
            ['clave' => 'modelo_default', 'valor' => 'claude-sonnet-5', 'created_at' => now(), 'updated_at' => now()],
            ['clave' => 'temperatura_default', 'valor' => '0.7', 'created_at' => now(), 'updated_at' => now()],
            ['clave' => 'max_tokens_default', 'valor' => '500', 'created_at' => now(), 'updated_at' => now()],
            ['clave' => 'system_prompt', 'valor' => 'Eres un asistente virtual útil y amigable.', 'created_at' => now(), 'updated_at' => now()],
            ['clave' => 'api_key', 'valor' => '', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chatbot_configuraciones');
    }
};
