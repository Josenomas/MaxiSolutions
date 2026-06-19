<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'planes';

    protected $fillable = [
        'producto_id',
        'nombre',
        'slug',
        'descripcion',
        'precio_mensual',
        'precio_anual',
        'caracteristicas',
        'limites',
        'activo',
        'orden',
    ];

    protected $casts = [
        'precio_mensual' => 'decimal:2',
        'precio_anual' => 'decimal:2',
        'caracteristicas' => 'array',
        'limites' => 'array',
        'activo' => 'boolean',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class);
    }

    /**
     * Verificar si tiene límite para una métrica
     */
    public function tieneLimite($metrica)
    {
        return isset($this->limites[$metrica]);
    }

    /**
     * Obtener límite de una métrica
     */
    public function getLimite($metrica)
    {
        return $this->limites[$metrica] ?? null;
    }

    /**
     * Es plan gratuito
     */
    public function esGratuito()
    {
        return $this->precio_mensual == 0 && $this->precio_anual == 0;
    }

    /**
     * Calcular ahorro anual
     */
    public function getAhorroAnualAttribute()
    {
        if ($this->precio_anual == 0) {
            return 0;
        }

        $costoMensualAnual = $this->precio_mensual * 12;
        return $costoMensualAnual - $this->precio_anual;
    }
}
