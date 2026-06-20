<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Plan;

class ProductosYPlanesSeeder extends Seeder
{
    public function run()
    {
        // 1. PRODUCTO PAES
        $paes = Producto::create([
            'nombre' => 'PAES - Preparación Universitaria',
            'slug' => 'paes',
            'icono' => 'academic-cap',
            'descripcion' => 'Plataforma completa de preparación para la Prueba de Acceso a la Educación Superior con IA',
            'url_base' => '/paes',
            'requiere_suscripcion' => true,
            'activo' => true,
            'configuracion' => [
                'materias' => ['matematica', 'lenguaje', 'ciencias', 'historia'],
                'features' => [
                    'preguntas_ia',
                    'simuladores',
                    'tutor_virtual',
                    'analisis_desempeno',
                    'estadisticas',
                ],
            ],
        ]);

        // PLANES PAES

        // Plan Gratuito
        Plan::create([
            'producto_id' => $paes->id,
            'nombre' => 'Plan Gratuito',
            'slug' => 'gratuito',
            'descripcion' => 'Prueba la plataforma con funciones básicas',
            'precio_mensual' => 0,
            'precio_anual' => 0,
            'caracteristicas' => [
                '10 preguntas por día',
                'Banco de preguntas básicas',
                'Estadísticas simples',
                'Sin tutor virtual',
                'Sin simuladores',
            ],
            'limites' => [
                'preguntas_dia' => 10,
                'preguntas_ia_dia' => 0,
                'consultas_tutor_dia' => 0,
                'simuladores_mes' => 0,
            ],
            'activo' => true,
            'orden' => 1,
        ]);

        // Plan Básico
        Plan::create([
            'producto_id' => $paes->id,
            'nombre' => 'Plan Básico',
            'slug' => 'basico',
            'descripcion' => 'Ideal para estudiantes que buscan preparación completa',
            'precio_mensual' => 5990,
            'precio_anual' => 59900, // ~$5.000/mes (17% descuento)
            'caracteristicas' => [
                '100 preguntas por día',
                'Preguntas generadas con IA',
                '20 consultas al tutor virtual por día',
                '5 simuladores completos al mes',
                'Estadísticas avanzadas',
                'Seguimiento de progreso',
                'Análisis de desempeño mensual',
            ],
            'limites' => [
                'preguntas_dia' => 100,
                'preguntas_ia_dia' => 50,
                'consultas_tutor_dia' => 20,
                'simuladores_mes' => 5,
            ],
            'activo' => true,
            'orden' => 2,
        ]);

        // Plan Premium
        Plan::create([
            'producto_id' => $paes->id,
            'nombre' => 'Plan Premium',
            'slug' => 'premium',
            'descripcion' => 'Preparación intensiva sin límites',
            'precio_mensual' => 12990,
            'precio_anual' => 119900, // ~$10.000/mes (23% descuento)
            'caracteristicas' => [
                'Preguntas ilimitadas',
                'Preguntas IA ilimitadas',
                'Tutor virtual ilimitado',
                'Simuladores ilimitados',
                'Análisis personalizado semanal',
                'Predicción de puntaje PAES',
                'Recomendaciones adaptativas con IA',
                'Informes descargables',
                'Soporte prioritario',
                'Acceso a material exclusivo',
            ],
            'limites' => null, // Sin límites
            'activo' => true,
            'orden' => 3,
        ]);

        // Plan Institucional (Colegios)
        Plan::create([
            'producto_id' => $paes->id,
            'nombre' => 'Plan Institucional',
            'slug' => 'institucional',
            'descripcion' => 'Para colegios y preuniversitarios',
            'precio_mensual' => 0, // Precio bajo consulta
            'precio_anual' => 0,
            'caracteristicas' => [
                'Usuarios ilimitados',
                'Dashboard administrativo',
                'Reportes por curso',
                'Seguimiento grupal',
                'Personalización de contenido',
                'Soporte dedicado',
                'Capacitación docente',
                'API para integración',
            ],
            'limites' => null,
            'activo' => true,
            'orden' => 4,
        ]);

        $this->command->info('✓ Producto PAES y planes creados exitosamente');


        // 2. PRODUCTO HATEACHISTOPHER (CHATBOT)
        $chatbot = Producto::create([
            'nombre' => 'HateaChistopher - Chatbot IA',
            'slug' => 'hateachistopher',
            'icono' => 'robot',
            'descripcion' => 'Asistente virtual inteligente disponible 24/7 para resolver tus consultas',
            'url_base' => 'https://hateachistopher.maxisolutions.cl',
            'requiere_suscripcion' => true,
            'activo' => true,
            'configuracion' => [
                'modelos' => ['gpt-3.5-turbo', 'claude', 'gemini'],
                'features' => ['conversaciones_ilimitadas', 'contexto_persistente', 'respuestas_inteligentes', 'personalizacion'],
            ],
        ]);

        Plan::create([
            'producto_id' => $chatbot->id,
            'nombre' => 'Plan Gratuito',
            'slug' => 'gratuito',
            'descripcion' => 'Prueba el chatbot con funciones básicas',
            'precio_mensual' => 0,
            'precio_anual' => 0,
            'caracteristicas' => ['50 mensajes por día', 'Modelo GPT-3.5', 'Historial de 7 días', 'Respuestas estándar'],
            'limites' => ['mensajes_dia' => 50, 'conversaciones_activas' => 5, 'historial_dias' => 7],
            'activo' => true,
            'orden' => 1
        ]);

        Plan::create([
            'producto_id' => $chatbot->id,
            'nombre' => 'Plan Básico',
            'slug' => 'basico',
            'descripcion' => 'Ideal para uso personal frecuente',
            'precio_mensual' => 3990,
            'precio_anual' => 39900,
            'caracteristicas' => ['500 mensajes por día', 'Modelos GPT-3.5 y Claude', 'Historial ilimitado', 'Respuestas personalizadas', 'Soporte por email'],
            'limites' => ['mensajes_dia' => 500, 'conversaciones_activas' => 50, 'historial_dias' => null],
            'activo' => true,
            'orden' => 2
        ]);

        Plan::create([
            'producto_id' => $chatbot->id,
            'nombre' => 'Plan Premium',
            'slug' => 'premium',
            'descripcion' => 'Para usuarios avanzados sin límites',
            'precio_mensual' => 9990,
            'precio_anual' => 99900,
            'caracteristicas' => ['Mensajes ilimitados', 'Todos los modelos (GPT-4, Claude, Gemini)', 'Historial ilimitado', 'Respuestas prioritarias', 'Personalización avanzada', 'Soporte prioritario', 'API de acceso'],
            'limites' => null,
            'activo' => true,
            'orden' => 3
        ]);

        $this->command->info('✓ Producto HateaChistopher y planes creados exitosamente');

        // 3. PRODUCTO ERP (Futuro)
        $erp = Producto::create([
            'nombre' => 'ERP Empresarial',
            'slug' => 'erp',
            'icono' => 'briefcase',
            'descripcion' => 'Sistema de planificación de recursos empresariales completo',
            'url_base' => '/erp',
            'requiere_suscripcion' => true,
            'activo' => false, // Por implementar
            'configuracion' => [
                'modulos' => ['inventario', 'ventas', 'compras', 'contabilidad', 'rrhh'],
            ],
        ]);

        // 3. PRODUCTO CRM (Futuro)
        $crm = Producto::create([
            'nombre' => 'CRM - Gestión de Clientes',
            'slug' => 'crm',
            'icono' => 'users',
            'descripcion' => 'Sistema de gestión de relaciones con clientes',
            'url_base' => '/crm',
            'requiere_suscripcion' => true,
            'activo' => false, // Por implementar
            'configuracion' => [
                'modulos' => ['contactos', 'oportunidades', 'pipeline', 'reportes'],
            ],
        ]);

        $this->command->info('✓ Productos futuros (ERP, CRM) creados');
    }
}
