<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'precio_base',
        'duracion_estimada',
        'imagen',
        'destacado',
        'estado'
    ];

    protected $casts = [
        'destacado' => 'boolean',
        'precio_base' => 'decimal:2'
    ];

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }
}
