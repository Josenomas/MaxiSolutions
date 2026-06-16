<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('servicio_id')->nullable()->constrained('servicios')->nullOnDelete();
            $table->string('nombre_cliente', 100);
            $table->string('email_cliente', 150);
            $table->string('telefono_cliente', 20)->nullable();
            $table->string('empresa', 150)->nullable();
            $table->text('descripcion_proyecto');
            $table->string('presupuesto_estimado', 50)->nullable();
            $table->date('fecha_inicio_deseada')->nullable();
            $table->enum('estado', ['pendiente', 'en_revision', 'cotizado', 'aceptado', 'rechazado', 'completado'])->default('pendiente');
            $table->text('notas_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('solicitudes');
    }
};
