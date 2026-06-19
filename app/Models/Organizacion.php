<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organizacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'organizaciones';

    protected $fillable = [
        'nombre',
        'slug',
        'tipo',
        'rut',
        'email',
        'telefono',
        'direccion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Usuarios de la organización
     */
    public function usuarios()
    {
        return $this->hasMany(User::class, 'organizacion_id');
    }

    /**
     * Suscripciones activas
     */
    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class);
    }

    /**
     * Suscripciones activas
     */
    public function suscripcionesActivas()
    {
        return $this->hasMany(Suscripcion::class)->where('estado', 'activa');
    }

    /**
     * Verificar si tiene acceso a un producto
     */
    public function tieneAcceso($productoSlug)
    {
        return $this->suscripcionesActivas()
            ->whereHas('producto', function ($query) use ($productoSlug) {
                $query->where('slug', $productoSlug);
            })
            ->exists();
    }

    /**
     * Obtener plan activo de un producto
     */
    public function planActivo($productoSlug)
    {
        $suscripcion = $this->suscripcionesActivas()
            ->whereHas('producto', function ($query) use ($productoSlug) {
                $query->where('slug', $productoSlug);
            })
            ->first();

        return $suscripcion?->plan;
    }

    /**
     * Verificar límite de uso
     */
    public function verificarLimite($productoSlug, $metrica, $cantidad = 1)
    {
        $plan = $this->planActivo($productoSlug);

        if (!$plan || !$plan->limites) {
            return true; // Sin límites o sin plan
        }

        $limites = $plan->limites;
        if (!isset($limites[$metrica])) {
            return true; // Esta métrica no tiene límite
        }

        $usoHoy = UsoProducto::where('organizacion_id', $this->id)
            ->whereHas('producto', function ($query) use ($productoSlug) {
                $query->where('slug', $productoSlug);
            })
            ->where('metrica', $metrica)
            ->whereDate('fecha', today())
            ->sum('cantidad');

        return ($usoHoy + $cantidad) <= $limites[$metrica];
    }

    /**
     * Registrar uso
     */
    public function registrarUso($productoId, $metrica, $cantidad = 1, $userId = null)
    {
        return UsoProducto::create([
            'organizacion_id' => $this->id,
            'producto_id' => $productoId,
            'user_id' => $userId ?? auth()->id(),
            'metrica' => $metrica,
            'cantidad' => $cantidad,
            'fecha' => today(),
        ]);
    }
}
