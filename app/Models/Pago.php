<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'solicitud_id',
        'usuario_id',
        'monto',
        'metodo_pago',
        'estado',
        'referencia_pago',
        'token',
        'buy_order',
        'response_data',
        'fecha_confirmacion'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'response_data' => 'array',
        'fecha_confirmacion' => 'datetime'
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
