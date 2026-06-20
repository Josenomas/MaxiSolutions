<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabla de usuarios del chatbot
        Schema::create('chatbot_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telefono', 20)->nullable();
            $table->enum('plan', ['gratuito', 'basico', 'premium'])->default('gratuito');
            $table->boolean('activo')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabla de conversaciones
        Schema::create('chatbot_conversaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('chatbot_users')->onDelete('cascade');
            $table->string('titulo')->default('Nueva conversación');
            $table->boolean('activa')->default(true);
            $table->timestamp('ultima_actividad')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('ultima_actividad');
        });

        // Tabla de mensajes
        Schema::create('chatbot_mensajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversacion_id')->constrained('chatbot_conversaciones')->onDelete('cascade');
            $table->enum('role', ['user', 'assistant', 'system'])->default('user');
            $table->text('contenido');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('conversacion_id');
            $table->index('created_at');
        });

        // Tabla de configuración del chatbot por usuario
        Schema::create('chatbot_configuraciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('chatbot_users')->onDelete('cascade');
            $table->string('modelo', 50)->default('gpt-3.5-turbo');
            $table->decimal('temperatura', 2, 1)->default(0.7);
            $table->integer('max_tokens')->default(500);
            $table->string('personalidad')->default('asistente');
            $table->timestamps();

            $table->unique('user_id');
        });

        // Tabla de límites de uso (para planes)
        Schema::create('chatbot_uso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('chatbot_users')->onDelete('cascade');
            $table->date('fecha');
            $table->integer('mensajes_enviados')->default(0);
            $table->integer('tokens_usados')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'fecha']);
            $table->index('fecha');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chatbot_uso');
        Schema::dropIfExists('chatbot_configuraciones');
        Schema::dropIfExists('chatbot_mensajes');
        Schema::dropIfExists('chatbot_conversaciones');
        Schema::dropIfExists('chatbot_users');
    }
};
