<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudHistorial extends Model
{
    use HasFactory;

    protected $table = 'solicitud_historial';

    protected $fillable = [
        'solicitud_id',
        'user_id',
        'accion',
        'campo',
        'valor_anterior',
        'valor_nuevo',
        'descripcion'
    ];

    // Relaciones
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper para crear registro de cambio de estado
    public static function registrarCambioEstado($solicitudId, $userId, $estadoAnterior, $estadoNuevo)
    {
        return self::create([
            'solicitud_id' => $solicitudId,
            'user_id' => $userId,
            'accion' => 'cambio_estado',
            'campo' => 'estado',
            'valor_anterior' => $estadoAnterior,
            'valor_nuevo' => $estadoNuevo,
            'descripcion' => "Estado cambiado de {$estadoAnterior} a {$estadoNuevo}"
        ]);
    }
}