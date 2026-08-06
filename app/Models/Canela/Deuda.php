<?php

namespace App\Models\Canela;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    use HasFactory;

    protected $table = 'canela_deudas';

    protected $fillable = [
        'cliente_id',
        'venta_id',
        'monto_total',
        'monto_pagado',
        'monto_pendiente',
        'estado',
        'fecha_vencimiento',
        'notas',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
        'monto_pendiente' => 'decimal:2',
        'fecha_vencimiento' => 'date',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function pagos()
    {
        return $this->hasMany(DeudaPago::class, 'deuda_id');
    }
}
