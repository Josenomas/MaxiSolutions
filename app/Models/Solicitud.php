<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'usuario_id',
        'servicio_id',
        'nombre_cliente',
        'email_cliente',
        'telefono_cliente',
        'empresa',
        'descripcion_proyecto',
        'presupuesto_estimado',
        'monto_cotizado',
        'fecha_estimada_entrega',
        'fecha_inicio_deseada',
        'estado',
        'notas_admin',
        'motivo_cancelacion'
    ];

    protected $casts = [
        'fecha_inicio_deseada' => 'date',
        'fecha_estimada_entrega' => 'date',
        'monto_cotizado' => 'decimal:2'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function comentarios()
    {
        return $this->hasMany(SolicitudComentario::class)->orderBy('created_at', 'desc');
    }

    public function historial()
    {
        return $this->hasMany(SolicitudHistorial::class)->orderBy('created_at', 'desc');
    }

    // Helper para obtener el color del estado
    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'secondary',
            'en_revision' => 'info',
            'cotizada' => 'warning',
            'aceptada' => 'primary',
            'en_desarrollo' => 'info',
            'completada' => 'success',
            'cancelada' => 'danger',
            default => 'secondary'
        };
    }

    // Helper para obtener el icono del estado
    public function getEstadoIconAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'fa-clock',
            'en_revision' => 'fa-search',
            'cotizada' => 'fa-file-invoice-dollar',
            'aceptada' => 'fa-check-circle',
            'en_desarrollo' => 'fa-code',
            'completada' => 'fa-check-double',
            'cancelada' => 'fa-times-circle',
            default => 'fa-question'
        };
    }
}