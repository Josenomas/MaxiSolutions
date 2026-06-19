<?php

namespace App\Models\Paes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $table = 'paes_materias';

    protected $fillable = [
        'nombre',
        'slug',
        'icono',
        'descripcion',
        'color',
        'activo',
        'orden',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Temas de esta materia
     */
    public function temas()
    {
        return $this->hasMany(Tema::class, 'materia_id')->orderBy('orden');
    }

    /**
     * Temas activos
     */
    public function temasActivos()
    {
        return $this->hasMany(Tema::class, 'materia_id')
            ->where('activo', true)
            ->orderBy('orden');
    }

    /**
     * Preguntas de esta materia (a través de temas)
     */
    public function preguntas()
    {
        return $this->hasManyThrough(
            Pregunta::class,
            Tema::class,
            'materia_id',
            'tema_id'
        );
    }

    /**
     * Sesiones de esta materia
     */
    public function sesiones()
    {
        return $this->hasMany(Sesion::class, 'materia_id');
    }

    /**
     * Análisis de esta materia
     */
    public function analisis()
    {
        return $this->hasMany(Analisis::class, 'materia_id');
    }

    /**
     * Progreso de usuarios en esta materia
     */
    public function progresos()
    {
        return $this->hasMany(Progreso::class, 'materia_id');
    }

    /**
     * Obtener estadísticas generales de la materia
     */
    public function estadisticas()
    {
        return [
            'total_temas' => $this->temas()->count(),
            'total_preguntas' => $this->preguntas()->count(),
            'total_sesiones' => $this->sesiones()->count(),
        ];
    }

    /**
     * Obtener progreso de un usuario en esta materia
     */
    public function progresoUsuario($userId)
    {
        return $this->progresos()
            ->where('user_id', $userId)
            ->whereNull('tema_id')
            ->first();
    }
}
