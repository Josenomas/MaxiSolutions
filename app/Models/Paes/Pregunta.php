<?php

namespace App\Models\Paes;

use App\Traits\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pregunta extends Model
{
    use HasFactory, SoftDeletes, TenantScope;

    protected $table = 'paes_preguntas';

    protected $fillable = [
        'organizacion_id',
        'tema_id',
        'enunciado',
        'contexto',
        'tipo',
        'dificultad',
        'fuente',
        'creado_por',
        'explicacion',
        'metadata',
        'verificada',
        'activo',
    ];

    protected $casts = [
        'metadata' => 'array',
        'verificada' => 'boolean',
        'activo' => 'boolean',
    ];

    /**
     * Tema al que pertenece
     */
    public function tema()
    {
        return $this->belongsTo(Tema::class, 'tema_id');
    }

    /**
     * Alternativas de la pregunta
     */
    public function alternativas()
    {
        return $this->hasMany(Alternativa::class, 'pregunta_id')->orderBy('orden');
    }

    /**
     * Alternativa correcta
     */
    public function alternativaCorrecta()
    {
        return $this->hasOne(Alternativa::class, 'pregunta_id')
            ->where('es_correcta', true);
    }

    /**
     * Usuario que creó la pregunta
     */
    public function creador()
    {
        return $this->belongsTo(\App\Models\User::class, 'creado_por');
    }

    /**
     * Respuestas de usuarios a esta pregunta
     */
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'pregunta_id');
    }

    /**
     * Chats de IA relacionados con esta pregunta
     */
    public function chats()
    {
        return $this->hasMany(ChatIA::class, 'pregunta_id');
    }

    /**
     * Materia (a través del tema)
     */
    public function materia()
    {
        return $this->hasOneThrough(
            Materia::class,
            Tema::class,
            'id',
            'id',
            'tema_id',
            'materia_id'
        );
    }

    /**
     * Verificar si una alternativa es correcta
     */
    public function esCorrecta($alternativaId)
    {
        return $this->alternativaCorrecta->id === $alternativaId;
    }

    /**
     * Obtener letra de alternativa correcta
     */
    public function getLetraCorrectaAttribute()
    {
        return $this->alternativaCorrecta?->letra;
    }

    /**
     * Obtener estadísticas de respuestas
     */
    public function estadisticasRespuestas()
    {
        $total = $this->respuestas()->count();

        if ($total === 0) {
            return [
                'total' => 0,
                'correctas' => 0,
                'incorrectas' => 0,
                'omitidas' => 0,
                'porcentaje_acierto' => 0,
            ];
        }

        $correctas = $this->respuestas()->where('es_correcta', true)->count();
        $omitidas = $this->respuestas()->where('omitida', true)->count();
        $incorrectas = $total - $correctas - $omitidas;

        return [
            'total' => $total,
            'correctas' => $correctas,
            'incorrectas' => $incorrectas,
            'omitidas' => $omitidas,
            'porcentaje_acierto' => round(($correctas / $total) * 100, 2),
        ];
    }

    /**
     * Nombre de dificultad legible
     */
    public function getDificultadNombreAttribute()
    {
        return [
            1 => 'Fácil',
            2 => 'Medio',
            3 => 'Difícil',
        ][$this->dificultad] ?? 'Medio';
    }

    /**
     * Scope: Solo preguntas verificadas
     */
    public function scopeVerificadas($query)
    {
        return $query->where('verificada', true);
    }

    /**
     * Scope: Por dificultad
     */
    public function scopeDificultad($query, $dificultad)
    {
        return $query->where('dificultad', $dificultad);
    }

    /**
     * Scope: Por fuente
     */
    public function scopeFuente($query, $fuente)
    {
        return $query->where('fuente', $fuente);
    }

    /**
     * Scope: Generadas por IA
     */
    public function scopeGeneradasIA($query)
    {
        return $query->where('fuente', 'ia');
    }

    /**
     * Scope: Manuales
     */
    public function scopeManuales($query)
    {
        return $query->where('fuente', 'manual');
    }
}
