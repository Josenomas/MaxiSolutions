<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plantilla;

class PlantillaSeeder extends Seeder
{
    public function run()
    {
        $plantillas = [
            // COMENTARIOS
            [
                'nombre' => 'Bienvenida - Solicitud Recibida',
                'tipo' => 'comentario',
                'contenido' => 'Hola {nombre_cliente}, hemos recibido tu solicitud #{solicitud_id} para {servicio}. Estamos revisando los detalles y nos pondremos en contacto contigo pronto. ¡Gracias por confiar en MaxiSolutions!',
                'descripcion' => 'Primer contacto cuando se recibe una solicitud',
                'activa' => true
            ],
            [
                'nombre' => 'Cotización Lista',
                'tipo' => 'comentario',
                'contenido' => 'Estimado/a {nombre_cliente}, hemos preparado una cotización para tu proyecto. El monto es de {monto_cotizado} y la fecha estimada de entrega es {fecha_estimada}. Por favor revisa los detalles y confirma si deseas proceder.',
                'descripcion' => 'Cuando se envía la cotización al cliente',
                'activa' => true
            ],
            [
                'nombre' => 'Solicitud Aceptada',
                'tipo' => 'comentario',
                'contenido' => '¡Excelente {nombre_cliente}! Tu proyecto ha sido aceptado y comenzaremos a trabajar de inmediato. Te mantendremos informado sobre el progreso. Estado actual: {estado}',
                'descripcion' => 'Cuando el cliente acepta la cotización',
                'activa' => true
            ],
            [
                'nombre' => 'Proyecto en Desarrollo',
                'tipo' => 'comentario',
                'contenido' => 'Hola {nombre_cliente}, te informamos que tu proyecto está actualmente en desarrollo. Todo va según lo planificado. Fecha estimada de entrega: {fecha_estimada}. Si tienes alguna pregunta, estamos aquí para ayudarte.',
                'descripcion' => 'Actualización de progreso',
                'activa' => true
            ],
            [
                'nombre' => 'Proyecto Completado',
                'tipo' => 'comentario',
                'contenido' => '¡Felicitaciones {nombre_cliente}! Tu proyecto ha sido completado exitosamente. Por favor revisa el resultado y háznoslo saber si necesitas algún ajuste. ¡Gracias por confiar en nosotros!',
                'descripcion' => 'Cuando el proyecto está terminado',
                'activa' => true
            ],
            [
                'nombre' => 'Solicitud de Información',
                'tipo' => 'comentario',
                'contenido' => 'Hola {nombre_cliente}, necesitamos información adicional para avanzar con tu solicitud de {servicio}. Por favor proporciona los detalles que te solicitamos para continuar. ¡Gracias!',
                'descripcion' => 'Cuando se necesita más info del cliente',
                'activa' => true
            ],
            [
                'nombre' => 'Recordatorio de Pago',
                'tipo' => 'comentario',
                'contenido' => 'Estimado/a {nombre_cliente}, te recordamos que tienes un pago pendiente de {monto_cotizado} para tu proyecto #{solicitud_id}. Puedes realizar el pago desde tu panel de cliente.',
                'descripcion' => 'Recordatorio amable de pago',
                'activa' => true
            ],

            // EMAILS
            [
                'nombre' => 'Email: Bienvenida',
                'tipo' => 'email',
                'asunto' => 'Solicitud #{solicitud_id} Recibida - MaxiSolutions',
                'contenido' => 'Estimado/a {nombre_cliente},

Hemos recibido tu solicitud para {servicio}.

Detalles de tu solicitud:
- ID: #{solicitud_id}
- Servicio: {servicio}
- Estado: {estado}

Nuestro equipo está revisando tu solicitud y nos pondremos en contacto contigo pronto.

Saludos cordiales,
Equipo MaxiSolutions
{año_actual}',
                'descripcion' => 'Email de confirmación de solicitud',
                'activa' => true
            ],
            [
                'nombre' => 'Email: Cotización Enviada',
                'tipo' => 'email',
                'asunto' => 'Cotización Lista para {servicio} - MaxiSolutions',
                'contenido' => 'Hola {nombre_cliente},

Hemos preparado la cotización para tu proyecto.

Detalles:
- Servicio: {servicio}
- Monto: {monto_cotizado}
- Fecha estimada de entrega: {fecha_estimada}

Por favor ingresa a tu panel de cliente para revisar los detalles completos.

¿Tienes preguntas? Contáctanos en {email_cliente}

Saludos,
MaxiSolutions',
                'descripcion' => 'Email cuando se envía cotización',
                'activa' => true
            ],
            [
                'nombre' => 'Email: Pago Confirmado',
                'tipo' => 'email',
                'asunto' => 'Pago Confirmado - Solicitud #{solicitud_id}',
                'contenido' => '¡Excelente {nombre_cliente}!

Hemos confirmado tu pago de {monto_cotizado}.

Tu proyecto #{solicitud_id} entrará en desarrollo de inmediato.

Fecha estimada de entrega: {fecha_estimada}

Te mantendremos informado sobre el progreso.

Gracias por confiar en MaxiSolutions,
Equipo MaxiSolutions',
                'descripcion' => 'Confirmación de pago recibido',
                'activa' => true
            ],
        ];

        foreach ($plantillas as $plantilla) {
            Plantilla::create($plantilla);
        }
    }
}