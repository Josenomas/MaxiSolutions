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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('admin_role', ['super_admin', 'admin_chatbot', 'admin_paes', 'admin_principal'])
                ->nullable()
                ->after('is_admin')
                ->comment('Rol de administrador: super_admin (acceso total), admin_chatbot (solo chatbot), admin_paes (solo PAES), admin_principal (solo dominio principal)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('admin_role');
        });
    }
};
