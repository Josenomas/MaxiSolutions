<?php

namespace App\Models\Paes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analisis extends Model
{
    use HasFactory;

    protected $table = 'paes_analisis';

    protected $fillable = [
        'user_id',
        'materia_id',
        'tema_id',
        'nivel_dominio',
        'fortalezas',
        'debilidades',
        'recomendaciones',
        'estadisticas',
        'fecha_analisis',
    ];

    protected $casts = [
        'nivel_dominio' => 'decimal:2',
        'estadisticas' => 'array',
        'fecha_analisis' => 'date',
    ];

    /**
     * Usuario analizado
     */
    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Materia analizada
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    /**
     * Tema analizado
     */
    public function tema()
    {
        return $this->belongsTo(Tema::class, 'tema_id');
    }

    /**
     * Obtener nivel en texto
     */
    public function getNivelTextoAttribute()
    {
        if ($this->nivel_dominio >= 80) {
            return 'Excelente';
        } elseif ($this->nivel_dominio >= 60) {
            return 'Bueno';
        } elseif ($this->nivel_dominio >= 40) {
            return 'Regular';
        } else {
            return 'Necesita mejorar';
        }
    }

    /**
     * Obtener color según nivel
     */
    public function getNivelColorAttribute()
    {
        if ($this->nivel_dominio >= 80) {
            return 'success';
        } elseif ($this->nivel_dominio >= 60) {
            return 'info';
        } elseif ($this->nivel_dominio >= 40) {
            return 'warning';
        } else {
            return 'danger';
        }
    }
}
