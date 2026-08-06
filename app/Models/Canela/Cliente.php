<?php

namespace App\Models\Canela;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'canela_clientes';

    protected $fillable = [
        'nombre',
        'rut',
        'telefono',
        'email',
        'direccion',
        'tipo',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relaciones
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }

    public function deudas()
    {
        return $this->hasMany(Deuda::class, 'cliente_id');
    }

    // Accesor para deuda total pendiente
    public function getDeudaTotalAttribute()
    {
        return $this->deudas()->where('estado', 'pendiente')->sum('monto_pendiente');
    }
}
