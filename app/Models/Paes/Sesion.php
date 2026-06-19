<?php

namespace App\Models\Paes;

use App\Traits\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
    use HasFactory, TenantScope;

    protected $table = 'paes_sesiones';

    protected $fillable = [
        'organizacion_id',
        'user_id',
        'materia_id',
        'tipo',
        'modo',
        'total_preguntas',
        'correctas',
        'incorrectas',
        'omitidas',
        'porcentaje',
        'tiempo_total_segundos',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'configuracion',
    ];

    protected $casts = [
        'porcentaje' => 'decimal:2',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'configuracion' => 'array',
    ];

    /**
     * Usuario que realizó la sesión
     */
    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Materia de la sesión
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    /**
     * Respuestas de la sesión
     */
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'sesion_id')->orderBy('numero_pregunta');
    }

    /**
     * Completar sesión
     */
    public function completar()
    {
        $this->fecha_fin = now();
        $this->estado = 'completada';

        // Calcular estadísticas
        $this->calcularEstadisticas();

        $this->save();

        // Actualizar progreso del usuario
        $this->actualizarProgreso();

        return $this;
    }

    /**
     * Calcular estadísticas de la sesión
     */
    public function calcularEstadisticas()
    {
        $respuestas = $this->respuestas;

        $this->total_preguntas = $respuestas->count();
        $this->correctas = $respuestas->where('es_correcta', true)->count();
        $this->omitidas = $respuestas->where('omitida', true)->count();
        $this->incorrectas = $this->total_preguntas - $this->correctas - $this->omitidas;

        if ($this->total_preguntas > 0) {
            $this->porcentaje = round(($this->correctas / $this->total_preguntas) * 100, 2);
        }

        // Calcular tiempo total
        if ($this->fecha_inicio && $this->fecha_fin) {
            $this->tiempo_total_segundos = $this->fecha_fin->diffInSeconds($this->fecha_inicio);
        }
    }

    /**
     * Actualizar progreso del usuario
     */
    private function actualizarProgreso()
    {
        if (!$this->materia_id) {
            return;
        }

        $progreso = Progreso::firstOrCreate([
            'user_id' => $this->user_id,
            'materia_id' => $this->materia_id,
            'tema_id' => null,
        ]);

        $progreso->preguntas_realizadas += $this->total_preguntas;
        $progreso->preguntas_correctas += $this->correctas;
        $progreso->porcentaje_acierto = $progreso->preguntas_realizadas > 0
            ? round(($progreso->preguntas_correctas / $progreso->preguntas_realizadas) * 100, 2)
            : 0;
        $progreso->ultima_practica = today();

        // Actualizar racha
        if ($progreso->ultima_practica && $progreso->ultima_practica->isToday()) {
            // Ya practicó hoy, mantener racha
        } elseif ($progreso->ultima_practica && $progreso->ultima_practica->isYesterday()) {
            // Practicó ayer, incrementar racha
            $progreso->racha_actual++;
            if ($progreso->racha_actual > $progreso->racha_maxima) {
                $progreso->racha_maxima = $progreso->racha_actual;
            }
        } else {
            // Se rompió la racha
            $progreso->racha_actual = 1;
        }

        $progreso->save();
    }

    /**
     * Obtener duración en formato legible
     */
    public function getDuracionLegibleAttribute()
    {
        if (!$this->tiempo_total_segundos) {
            return 'N/A';
        }

        $minutos = floor($this->tiempo_total_segundos / 60);
        $segundos = $this->tiempo_total_segundos % 60;

        if ($minutos > 0) {
            return "{$minutos} min {$segundos} seg";
        }

        return "{$segundos} seg";
    }

    /**
     * Obtener puntaje PAES estimado (escala 150-850)
     */
    public function getPuntajePaesEstimadoAttribute()
    {
        // Fórmula simplificada de conversión
        // 0% = 150 puntos, 100% = 850 puntos
        return round(150 + ($this->porcentaje * 7));
    }

    /**
     * Scope: Sesiones completadas
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    /**
     * Scope: Sesiones en progreso
     */
    public function scopeEnProgreso($query)
    {
        return $query->where('estado', 'en_progreso');
    }

    /**
     * Scope: Por tipo
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
