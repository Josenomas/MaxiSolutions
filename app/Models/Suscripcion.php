<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suscripcion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'suscripciones';

    protected $fillable = [
        'organizacion_id',
        'producto_id',
        'plan_id',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'proxima_facturacion',
        'periodo',
        'precio',
        'configuracion',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'proxima_facturacion' => 'date',
        'precio' => 'decimal:2',
        'configuracion' => 'array',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }

    /**
     * Verificar si está activa
     */
    public function estaActiva()
    {
        return $this->estado === 'activa' &&
               (!$this->fecha_fin || $this->fecha_fin->isFuture());
    }

    /**
     * Verificar si está vencida
     */
    public function estaVencida()
    {
        return $this->fecha_fin && $this->fecha_fin->isPast();
    }

    /**
     * Renovar suscripción
     */
    public function renovar()
    {
        if ($this->periodo === 'mensual') {
            $this->proxima_facturacion = now()->addMonth();
            $this->fecha_fin = now()->addMonth();
        } else {
            $this->proxima_facturacion = now()->addYear();
            $this->fecha_fin = now()->addYear();
        }

        $this->estado = 'activa';
        $this->save();

        return $this;
    }

    /**
     * Cancelar suscripción
     */
    public function cancelar()
    {
        $this->estado = 'cancelada';
        $this->save();

        return $this;
    }

    /**
     * Suspender suscripción
     */
    public function suspender()
    {
        $this->estado = 'suspendida';
        $this->save();

        return $this;
    }
}
