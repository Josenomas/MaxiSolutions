<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // login_failed, login_success, unauthorized_access, data_change, etc.
            $table->string('severity')->default('info'); // info, warning, critical
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable(); // GET, POST, etc.
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Datos adicionales
            $table->timestamp('created_at')->useCurrent();

            // Índices para búsquedas rápidas
            $table->index('event_type');
            $table->index('severity');
            $table->index('user_id');
            $table->index('ip_address');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('security_logs');
    }
};
