<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait TenantScope
{
    /**
     * Boot del trait - Aplica scope automático por organización
     */
    protected static function bootTenantScope()
    {
        // Al crear un modelo, asignar automáticamente la organización actual
        static::creating(function (Model $model) {
            if (!$model->organizacion_id && auth()->check() && auth()->user()->organizacion_id) {
                $model->organizacion_id = auth()->user()->organizacion_id;
            }
        });

        // Aplicar scope global para filtrar por organización
        static::addGlobalScope('organizacion', function (Builder $builder) {
            if (auth()->check() && auth()->user()->organizacion_id) {
                $builder->where('organizacion_id', auth()->user()->organizacion_id);
            }
        });
    }

    /**
     * Relación con la organización
     */
    public function organizacion()
    {
        return $this->belongsTo(\App\Models\Organizacion::class);
    }

    /**
     * Scope para acceder a datos de todas las organizaciones (solo admin)
     */
    public function scopeAllTenants(Builder $query)
    {
        return $query->withoutGlobalScope('organizacion');
    }

    /**
     * Scope para filtrar por organización específica
     */
    public function scopeForTenant(Builder $query, $organizacionId)
    {
        return $query->withoutGlobalScope('organizacion')
                    ->where('organizacion_id', $organizacionId);
    }
}
