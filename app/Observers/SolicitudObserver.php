<?php

namespace App\Observers;

use App\Models\Solicitud;
use App\Models\SolicitudHistorial;
use Illuminate\Support\Facades\Auth;

class SolicitudObserver
{
    public function updated(Solicitud $solicitud)
    {
        $cambios = $solicitud->getDirty();
        
        foreach ($cambios as $campo => $valorNuevo) {
            // Solo registrar cambios relevantes
            if (in_array($campo, ['estado', 'monto_cotizado', 'fecha_estimada_entrega', 'notas_admin'])) {
                $valorAnterior = $solicitud->getOriginal($campo);
                
                $descripcion = match($campo) {
                    'estado' => "Estado cambiado de {$valorAnterior} a {$valorNuevo}",
                    'monto_cotizado' => "Monto cotizado actualizado a ${$valorNuevo}",
                    'fecha_estimada_entrega' => "Fecha estimada de entrega actualizada",
                    'notas_admin' => "Notas administrativas actualizadas",
                    default => "Campo {$campo} actualizado"
                };
                
                SolicitudHistorial::create([
                    'solicitud_id' => $solicitud->id,
                    'user_id' => Auth::id(),
                    'accion' => 'cambio_' . $campo,
                    'campo' => $campo,
                    'valor_anterior' => $valorAnterior,
                    'valor_nuevo' => $valorNuevo,
                    'descripcion' => $descripcion
                ]);
            }
        }
    }

    public function created(Solicitud $solicitud)
    {
        SolicitudHistorial::create([
            'solicitud_id' => $solicitud->id,
            'user_id' => $solicitud->usuario_id,
            'accion' => 'solicitud_creada',
            'campo' => null,
            'valor_anterior' => null,
            'valor_nuevo' => null,
            'descripcion' => 'Solicitud creada por ' . $solicitud->nombre_cliente
        ]);
    }
}