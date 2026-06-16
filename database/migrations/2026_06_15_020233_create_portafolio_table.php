<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('portafolio', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();
            $table->enum('categoria', ['desarrollo_web', 'capacitacion', 'consultoria', 'mantenimiento', 'otro']);
            $table->string('imagen_principal')->nullable();
            $table->string('url_proyecto')->nullable();
            $table->string('cliente', 100)->nullable();
            $table->date('fecha_proyecto')->nullable();
            $table->string('tecnologias')->nullable();
            $table->boolean('destacado')->default(false);
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('portafolio');
    }
};
