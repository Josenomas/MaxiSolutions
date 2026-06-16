<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Modificar el enum de estados
        DB::statement("ALTER TABLE solicitudes MODIFY COLUMN estado ENUM('pendiente', 'en_revision', 'cotizada', 'aceptada', 'en_desarrollo', 'completada', 'cancelada') DEFAULT 'pendiente'");

        // Agregar campos adicionales
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->decimal('monto_cotizado', 10, 2)->nullable()->after('presupuesto_estimado');
            $table->date('fecha_estimada_entrega')->nullable()->after('monto_cotizado');
            $table->text('motivo_cancelacion')->nullable()->after('notas_admin');
        });
    }

    public function down()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn(['monto_cotizado', 'fecha_estimada_entrega', 'motivo_cancelacion']);
        });

        DB::statement("ALTER TABLE solicitudes MODIFY COLUMN estado ENUM('pendiente', 'en_revision', 'cotizado', 'aceptado', 'rechazado', 'completado') DEFAULT 'pendiente'");
    }
};