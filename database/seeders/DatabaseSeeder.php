<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Servicio;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Admin MaxiSolutions',
            'email' => 'admin@maxisolutions.com',
            'password' => Hash::make('password'),
            'tipo_usuario' => 'admin'
        ]);

        // Crear servicios iniciales
        Servicio::create([
            'nombre' => 'Desarrollo Web Personalizado',
            'descripcion' => 'Creación de sitios web a medida según tus necesidades y objetivos de negocio.',
            'categoria' => 'desarrollo_web',
            'precio_base' => 500.00,
            'duracion_estimada' => '2-4 semanas',
            'destacado' => true,
            'estado' => 'activo'
        ]);

        Servicio::create([
            'nombre' => 'Capacitación en Desarrollo de Software',
            'descripcion' => 'Clases personalizadas de programación, frameworks y mejores prácticas.',
            'categoria' => 'capacitacion',
            'precio_base' => 50.00,
            'duracion_estimada' => '1 hora',
            'destacado' => true,
            'estado' => 'activo'
        ]);

        Servicio::create([
            'nombre' => 'Consultoría de Sistemas',
            'descripcion' => 'Asesoría técnica para optimizar tus procesos y sistemas informáticos.',
            'categoria' => 'consultoria',
            'precio_base' => 75.00,
            'duracion_estimada' => '1-2 horas',
            'destacado' => false,
            'estado' => 'activo'
        ]);

        Servicio::create([
            'nombre' => 'Mantenimiento Web',
            'descripcion' => 'Soporte y actualización continua de tu sitio web o aplicación.',
            'categoria' => 'mantenimiento',
            'precio_base' => 100.00,
            'duracion_estimada' => 'Mensual',
            'destacado' => false,
            'estado' => 'activo'
        ]);
    }
}
