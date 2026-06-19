<?php

namespace App\Models\Paes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progreso extends Model
{
    use HasFactory;

    protected $table = 'paes_progreso';

    protected $fillable = [
        'user_id',
        'materia_id',
        'tema_id',
        'preguntas_realizadas',
        'preguntas_correctas',
        'porcentaje_acierto',
        'tiempo_promedio_segundos',
        'racha_actual',
        'racha_maxima',
        'ultima_practica',
    ];

    protected $casts = [
        'porcentaje_acierto' => 'decimal:2',
        'ultima_practica' => 'date',
    ];

    /**
     * Usuario del progreso
     */
    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Materia del progreso
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    /**
     * Tema del progreso
     */
    public function tema()
    {
        return $this->belongsTo(Tema::class, 'tema_id');
    }

    /**
     * Obtener preguntas incorrectas
     */
    public function getPreguntasIncorrectasAttribute()
    {
        return $this->preguntas_realizadas - $this->preguntas_correctas;
    }

    /**
     * Obtener nivel en texto
     */
    public function getNivelTextoAttribute()
    {
        if ($this->porcentaje_acierto >= 80) {
            return 'Avanzado';
        } elseif ($this->porcentaje_acierto >= 60) {
            return 'Intermedio';
        } elseif ($this->porcentaje_acierto >= 40) {
            return 'Básico';
        } else {
            return 'Inicial';
        }
    }

    /**
     * Verificar si practicó hoy
     */
    public function practicoHoy()
    {
        return $this->ultima_practica && $this->ultima_practica->isToday();
    }

    /**
     * Días sin practicar
     */
    public function diasSinPracticar()
    {
        if (!$this->ultima_practica) {
            return null;
        }

        return today()->diffInDays($this->ultima_practica);
    }
}
