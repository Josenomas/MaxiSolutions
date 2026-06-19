<?php

namespace App\Models\Paes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternativa extends Model
{
    use HasFactory;

    protected $table = 'paes_alternativas';

    protected $fillable = [
        'pregunta_id',
        'letra',
        'texto',
        'es_correcta',
        'explicacion',
        'orden',
    ];

    protected $casts = [
        'es_correcta' => 'boolean',
    ];

    /**
     * Pregunta a la que pertenece
     */
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'pregunta_id');
    }

    /**
     * Respuestas de usuarios que eligieron esta alternativa
     */
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'alternativa_id');
    }

    /**
     * Obtener estadísticas de selección
     */
    public function estadisticasSeleccion()
    {
        $totalRespuestas = $this->pregunta->respuestas()->count();

        if ($totalRespuestas === 0) {
            return [
                'veces_seleccionada' => 0,
                'porcentaje' => 0,
            ];
        }

        $vecesSeleccionada = $this->respuestas()->count();

        return [
            'veces_seleccionada' => $vecesSeleccionada,
            'porcentaje' => round(($vecesSeleccionada / $totalRespuestas) * 100, 2),
        ];
    }
}
