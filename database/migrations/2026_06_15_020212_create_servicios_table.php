<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->enum('categoria', ['desarrollo_web', 'capacitacion', 'consultoria', 'mantenimiento', 'otro']);
            $table->decimal('precio_base', 10, 2)->nullable();
            $table->string('duracion_estimada', 50)->nullable();
            $table->string('imagen')->nullable();
            $table->boolean('destacado')->default(false);
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servicios');
    }
};
