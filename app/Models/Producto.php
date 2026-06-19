<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'slug',
        'icono',
        'descripcion',
        'url_base',
        'requiere_suscripcion',
        'activo',
        'configuracion',
    ];

    protected $casts = [
        'requiere_suscripcion' => 'boolean',
        'activo' => 'boolean',
        'configuracion' => 'array',
    ];

    /**
     * Planes disponibles para este producto
     */
    public function planes()
    {
        return $this->hasMany(Plan::class)->orderBy('orden');
    }

    /**
     * Planes activos
     */
    public function planesActivos()
    {
        return $this->hasMany(Plan::class)->where('activo', true)->orderBy('orden');
    }

    /**
     * Suscripciones a este producto
     */
    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class);
    }

    /**
     * Verificar si está activo
     */
    public function estaActivo()
    {
        return $this->activo;
    }
}
