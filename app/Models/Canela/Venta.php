<?php

namespace App\Models\Canela;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'canela_ventas';

    protected $fillable = [
        'cliente_id',
        'usuario_id',
        'total',
        'descuento',
        'total_final',
        'tipo_pago',
        'estado',
        'notas',
        'fecha_venta',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total_final' => 'decimal:2',
        'fecha_venta' => 'datetime',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class, 'venta_id');
    }

    public function deuda()
    {
        return $this->hasOne(Deuda::class, 'venta_id');
    }
}
