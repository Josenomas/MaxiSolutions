<?php

namespace App\Models\Canela;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaPago extends Model
{
    use HasFactory;

    protected $table = 'canela_deuda_pagos';

    protected $fillable = [
        'deuda_id',
        'usuario_id',
        'monto',
        'metodo_pago',
        'notas',
        'fecha_pago',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    // Relaciones
    public function deuda()
    {
        return $this->belongsTo(Deuda::class, 'deuda_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
