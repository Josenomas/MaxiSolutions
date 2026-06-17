<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Cambiar el tipo de columna a VARCHAR para soportar webpay y flow
        DB::statement("ALTER TABLE pagos MODIFY metodo_pago VARCHAR(50)");
    }

    public function down()
    {
        // Revertir a ENUM original
        DB::statement("ALTER TABLE pagos MODIFY metodo_pago ENUM('paypal', 'stripe', 'mercadopago', 'transferencia', 'otro')");
    }
};
