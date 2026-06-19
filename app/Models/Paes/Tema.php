<?php

namespace App\Models\Paes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;

    protected $table = 'paes_temas';

    protected $fillable = [
        'materia_id',
        'nombre',
        'slug',
        'descripcion',
        'dificultad',
        'activo',
        'orden',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Materia a la que pertenece
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    /**
     * Preguntas de este tema
     */
    public function preguntas()
    {
        return $this->hasMany(Pregunta::class, 'tema_id');
    }

    /**
     * Preguntas activas
     */
    public function preguntasActivas()
    {
        return $this->hasMany(Pregunta::class, 'tema_id')
            ->where('activo', true);
    }

    /**
     * Análisis de este tema
     */
    public function analisis()
    {
        return $this->hasMany(Analisis::class, 'tema_id');
    }

    /**
     * Progreso de usuarios en este tema
     */
    public function progresos()
    {
        return $this->hasMany(Progreso::class, 'tema_id');
    }

    /**
     * Obtener preguntas aleatorias del tema
     */
    public function preguntasAleatorias($cantidad = 10, $dificultad = null)
    {
        $query = $this->preguntasActivas();

        if ($dificultad) {
            $query->where('dificultad', $dificultad);
        }

        return $query->inRandomOrder()->limit($cantidad)->get();
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
}
