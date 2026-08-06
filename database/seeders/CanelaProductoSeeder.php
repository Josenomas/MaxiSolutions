<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CanelaProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Actualizar o crear el producto Canela Masandería
        \App\Models\Producto::updateOrCreate(
            ['slug' => 'canela-masanderia'],
            [
                'nombre' => 'Canela Masandería',
                'icono' => 'fa-bread-slice',
                'descripcion' => 'Sistema administrativo integral para gestión de panadería y pastelería. Controla ventas, inventario, compras, deudas y flujo de caja.',
                'url_base' => 'https://canelamasanderia.maxisolutions.cl',
                'requiere_suscripcion' => false,
                'activo' => true,
                'orden' => 1,
                'configuracion' => [
                    'permite_registro' => false,
                    'solo_admin' => true,
                ],
            ]
        );
    }
}
