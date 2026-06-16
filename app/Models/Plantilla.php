<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo',
        'asunto',
        'contenido',
        'descripcion',
        'activa',
        'veces_usada'
    ];

    protected $casts = [
        'activa' => 'boolean'
    ];

    // Procesar variables dinámicas
    public function procesarVariables(Solicitud $solicitud, array $datosAdicionales = [])
    {
        $variables = [
            '{nombre_cliente}' => $solicitud->nombre_cliente,
            '{email_cliente}' => $solicitud->email_cliente,
            '{telefono_cliente}' => $solicitud->telefono_cliente ?? 'No proporcionado',
            '{empresa}' => $solicitud->empresa ?? 'N/A',
            '{servicio}' => $solicitud->servicio->nombre ?? 'Servicio personalizado',
            '{estado}' => ucfirst(str_replace('_', ' ', $solicitud->estado)),
            '{monto_cotizado}' => $solicitud->monto_cotizado ? '$' . number_format($solicitud->monto_cotizado, 0, ',', '.') . ' CLP' : 'Por definir',
            '{fecha_estimada}' => $solicitud->fecha_estimada_entrega ? $solicitud->fecha_estimada_entrega->format('d/m/Y') : 'Por definir',
            '{solicitud_id}' => $solicitud->id,
            '{fecha_actual}' => now()->format('d/m/Y'),
            '{año_actual}' => now()->year,
        ];

        // Agregar datos adicionales si existen
        $variables = array_merge($variables, $datosAdicionales);

        // Reemplazar variables en el contenido
        $contenidoProcesado = str_replace(
            array_keys($variables),
            array_values($variables),
            $this->contenido
        );

        return $contenidoProcesado;
    }

    // Procesar asunto (solo para emails)
    public function procesarAsunto(Solicitud $solicitud)
    {
        if (!$this->asunto) {
            return null;
        }

        $variables = [
            '{nombre_cliente}' => $solicitud->nombre_cliente,
            '{servicio}' => $solicitud->servicio->nombre ?? 'Servicio',
            '{solicitud_id}' => $solicitud->id,
        ];

        return str_replace(
            array_keys($variables),
            array_values($variables),
            $this->asunto
        );
    }

    // Incrementar contador de uso
    public function incrementarUso()
    {
        $this->increment('veces_usada');
    }

    // Scope: solo plantillas activas
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    // Scope: por tipo
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}