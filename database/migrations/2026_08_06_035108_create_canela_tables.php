<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Sistema de gestión Canela Masandería
     */
    public function up()
    {
        // Tabla de categorías de productos
        Schema::create('canela_categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Tabla de productos
        Schema::create('canela_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('canela_categorias')->onDelete('cascade');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio_venta', 10, 2);
            $table->decimal('precio_costo', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->default(0);
            $table->string('unidad')->default('unidad'); // unidad, kg, docena, etc.
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Tabla de clientes
        Schema::create('canela_clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('rut')->nullable()->unique();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->text('direccion')->nullable();
            $table->enum('tipo', ['regular', 'mayorista'])->default('regular');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Tabla de proveedores
        Schema::create('canela_proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('rut')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->text('direccion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Tabla de ventas
        Schema::create('canela_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('canela_clientes')->onDelete('set null');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total_final', 10, 2);
            $table->enum('tipo_pago', ['efectivo', 'tarjeta', 'transferencia', 'credito'])->default('efectivo');
            $table->enum('estado', ['completada', 'pendiente', 'cancelada'])->default('completada');
            $table->text('notas')->nullable();
            $table->timestamp('fecha_venta');
            $table->timestamps();
        });

        // Tabla de detalle de ventas
        Schema::create('canela_venta_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('canela_ventas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('canela_productos')->onDelete('cascade');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        // Tabla de compras
        Schema::create('canela_compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->nullable()->constrained('canela_proveedores')->onDelete('set null');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total', 10, 2);
            $table->enum('tipo_pago', ['efectivo', 'tarjeta', 'transferencia', 'credito'])->default('efectivo');
            $table->enum('estado', ['completada', 'pendiente', 'cancelada'])->default('completada');
            $table->text('notas')->nullable();
            $table->timestamp('fecha_compra');
            $table->timestamps();
        });

        // Tabla de detalle de compras
        Schema::create('canela_compra_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained('canela_compras')->onDelete('cascade');
            $table->string('descripcion'); // puede ser producto o insumo genérico
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        // Tabla de deudas/cuentas por cobrar
        Schema::create('canela_deudas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('canela_clientes')->onDelete('cascade');
            $table->foreignId('venta_id')->nullable()->constrained('canela_ventas')->onDelete('set null');
            $table->decimal('monto_total', 10, 2);
            $table->decimal('monto_pagado', 10, 2)->default(0);
            $table->decimal('monto_pendiente', 10, 2);
            $table->enum('estado', ['pendiente', 'pagada', 'vencida'])->default('pendiente');
            $table->date('fecha_vencimiento')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        // Tabla de pagos de deudas
        Schema::create('canela_deuda_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deuda_id')->constrained('canela_deudas')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia'])->default('efectivo');
            $table->text('notas')->nullable();
            $table->timestamp('fecha_pago');
            $table->timestamps();
        });

        // Tabla de caja/flujo de efectivo
        Schema::create('canela_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo', ['ingreso', 'egreso', 'apertura', 'cierre'])->default('ingreso');
            $table->decimal('monto', 10, 2);
            $table->string('concepto');
            $table->text('descripcion')->nullable();
            $table->decimal('saldo_anterior', 10, 2)->nullable();
            $table->decimal('saldo_actual', 10, 2)->nullable();
            $table->timestamp('fecha_movimiento');
            $table->timestamps();
        });

        // Tabla de gastos operacionales
        Schema::create('canela_gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->string('categoria'); // luz, agua, arriendo, sueldos, etc.
            $table->decimal('monto', 10, 2);
            $table->text('descripcion')->nullable();
            $table->date('fecha_gasto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('canela_gastos');
        Schema::dropIfExists('canela_caja');
        Schema::dropIfExists('canela_deuda_pagos');
        Schema::dropIfExists('canela_deudas');
        Schema::dropIfExists('canela_compra_detalles');
        Schema::dropIfExists('canela_compras');
        Schema::dropIfExists('canela_venta_detalles');
        Schema::dropIfExists('canela_ventas');
        Schema::dropIfExists('canela_proveedores');
        Schema::dropIfExists('canela_clientes');
        Schema::dropIfExists('canela_productos');
        Schema::dropIfExists('canela_categorias');
    }
};
