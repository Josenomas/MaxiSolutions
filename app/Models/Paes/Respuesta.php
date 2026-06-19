<?php

namespace App\Models\Paes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;

    protected $table = 'paes_respuestas';

    protected $fillable = [
        'sesion_id',
        'pregunta_id',
        'alternativa_id',
        'es_correcta',
        'omitida',
        'tiempo_segundos',
        'numero_pregunta',
    ];

    protected $casts = [
        'es_correcta' => 'boolean',
        'omitida' => 'boolean',
    ];

    /**
     * Sesión a la que pertenece
     */
    public function sesion()
    {
        return $this->belongsTo(Sesion::class, 'sesion_id');
    }

    /**
     * Pregunta respondida
     */
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'pregunta_id');
    }

    /**
     * Alternativa seleccionada
     */
    public function alternativa()
    {
        return $this->belongsTo(Alternativa::class, 'alternativa_id');
    }

    /**
     * Obtener alternativa correcta de la pregunta
     */
    public function alternativaCorrecta()
    {
        return $this->pregunta->alternativaCorrecta;
    }

    /**
     * Verificar si la respuesta es correcta y actualizar
     */
    public function verificar()
    {
        if ($this->omitida) {
            $this->es_correcta = false;
            $this->save();
            return false;
        }

        $this->es_correcta = $this->pregunta->esCorrecta($this->alternativa_id);
        $this->save();

        return $this->es_correcta;
    }
}
