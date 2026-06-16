<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plantillas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100); // Nombre de la plantilla
            $table->enum('tipo', ['comentario', 'email'])->default('comentario');
            $table->string('asunto')->nullable(); // Solo para emails
            $table->text('contenido'); // Contenido con variables {nombre_cliente}, etc.
            $table->text('descripcion')->nullable(); // Descripción de cuándo usar
            $table->boolean('activa')->default(true);
            $table->integer('veces_usada')->default(0); // Contador de uso
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plantillas');
    }
};