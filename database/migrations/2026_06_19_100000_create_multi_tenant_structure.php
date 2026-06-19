<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Organizaciones (multi-tenant base)
        Schema::create('organizaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('tipo')->default('empresa'); // empresa, institucion, personal
            $table->string('rut')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->text('direccion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Productos SaaS disponibles
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // PAES, ERP, CRM, etc.
            $table->string('slug')->unique(); // paes, erp, crm
            $table->string('icono')->nullable();
            $table->text('descripcion');
            $table->string('url_base'); // /paes, /erp, /crm
            $table->boolean('requiere_suscripcion')->default(true);
            $table->boolean('activo')->default(true);
            $table->json('configuracion')->nullable(); // APIs, features, etc.
            $table->timestamps();
        });

        // 3. Planes de suscripción
        Schema::create('planes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->string('nombre'); // Gratuito, Básico, Premium
            $table->string('slug');
            $table->text('descripcion')->nullable();
            $table->decimal('precio_mensual', 10, 2)->default(0);
            $table->decimal('precio_anual', 10, 2)->default(0);
            $table->json('caracteristicas'); // ["100 preguntas/día", "Tutor IA", "Simuladores"]
            $table->json('limites')->nullable(); // {"preguntas_dia": 100, "simuladores_mes": 5}
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // 4. Suscripciones (relación org-producto-plan)
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacion_id')->constrained('organizaciones')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('planes')->onDelete('cascade');
            $table->string('estado')->default('activa'); // activa, cancelada, suspendida, vencida
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->date('proxima_facturacion')->nullable();
            $table->string('periodo')->default('mensual'); // mensual, anual
            $table->decimal('precio', 10, 2);
            $table->json('configuracion')->nullable(); // Personalización por org
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organizacion_id', 'producto_id', 'estado']);
        });

        // 5. Agregar organizacion_id a tabla users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('organizacion_id')->nullable()->after('id')->constrained('organizaciones')->onDelete('cascade');
            $table->string('rol_organizacion')->nullable()->after('tipo_usuario'); // owner, admin, member
        });

        // 6. Permisos por producto
        Schema::create('permisos_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->json('permisos'); // ["ver", "crear", "editar", "eliminar"]
            $table->timestamps();

            $table->unique(['user_id', 'producto_id']);
        });

        // 7. Uso y métricas (para límites de plan)
        Schema::create('uso_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacion_id')->constrained('organizaciones')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('metrica'); // preguntas_generadas, simuladores_realizados, api_calls
            $table->integer('cantidad')->default(1);
            $table->date('fecha');
            $table->timestamps();

            $table->index(['organizacion_id', 'producto_id', 'fecha']);
        });

        // 8. Facturación
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacion_id')->constrained('organizaciones')->onDelete('cascade');
            $table->foreignId('suscripcion_id')->constrained('suscripciones')->onDelete('cascade');
            $table->string('numero_factura')->unique();
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('iva', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('estado')->default('pendiente'); // pendiente, pagada, vencida, cancelada
            $table->string('metodo_pago')->nullable();
            $table->foreignId('pago_id')->nullable()->constrained('pagos')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facturas');
        Schema::dropIfExists('uso_producto');
        Schema::dropIfExists('permisos_producto');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organizacion_id']);
            $table->dropColumn(['organizacion_id', 'rol_organizacion']);
        });

        Schema::dropIfExists('suscripciones');
        Schema::dropIfExists('planes');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('organizaciones');
    }
};
