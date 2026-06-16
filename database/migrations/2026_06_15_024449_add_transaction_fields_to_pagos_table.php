<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('token')->nullable()->after('referencia_pago');
            $table->string('buy_order')->nullable()->after('token');
            $table->text('response_data')->nullable()->after('buy_order');
            $table->timestamp('fecha_confirmacion')->nullable()->after('response_data');
        });
    }

    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn(['token', 'buy_order', 'response_data', 'fecha_confirmacion']);
        });
    }
};
