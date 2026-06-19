<?php

namespace App\Models\Paes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;

    protected $table = 'paes_metas';

    protected $fillable = [
        'user_id',
        'tipo',
        'objetivo',
        'progreso_actual',
        'fecha_inicio',
        'fecha_limite',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_limite' => 'date',
    ];

    /**
     * Usuario de la meta
     */
    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Actualizar progreso
     */
    public function actualizarProgreso($nuevoProgreso)
    {
        $this->progreso_actual = $nuevoProgreso;

        if ($this->progreso_actual >= $this->objetivo) {
            $this->estado = 'completada';
        }

        $this->save();
    }

    /**
     * Porcentaje de avance
     */
    public function getPorcentajeAvanceAttribute()
    {
        if ($this->objetivo == 0) {
            return 0;
        }

        return min(100, round(($this->progreso_actual / $this->objetivo) * 100, 2));
    }

    /**
     * Días restantes
     */
    public function getDiasRestantesAttribute()
    {
        if (!$this->fecha_limite) {
            return null;
        }

        return today()->diffInDays($this->fecha_limite, false);
    }

    /**
     * Verificar si está vencida
     */
    public function estaVencida()
    {
        return $this->fecha_limite && $this->fecha_limite->isPast() && $this->estado !== 'completada';
    }

    /**
     * Abandonar meta
     */
    public function abandonar()
    {
        $this->estado = 'abandonada';
        $this->save();
    }

    /**
     * Scope: Activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    /**
     * Scope: Completadas
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    /**
     * Scope: Por tipo
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
