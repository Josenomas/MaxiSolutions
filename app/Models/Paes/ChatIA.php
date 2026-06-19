<?php

namespace App\Models\Paes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatIA extends Model
{
    use HasFactory;

    protected $table = 'paes_chat_ia';

    protected $fillable = [
        'user_id',
        'pregunta_id',
        'tema_id',
        'pregunta_usuario',
        'respuesta_ia',
        'tipo',
        'tokens_usados',
        'util',
    ];

    protected $casts = [
        'util' => 'boolean',
    ];

    /**
     * Usuario que hizo la pregunta
     */
    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Pregunta relacionada (si aplica)
     */
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'pregunta_id');
    }

    /**
     * Tema relacionado
     */
    public function tema()
    {
        return $this->belongsTo(Tema::class, 'tema_id');
    }

    /**
     * Marcar como útil
     */
    public function marcarUtil()
    {
        $this->util = true;
        $this->save();
    }

    /**
     * Marcar como no útil
     */
    public function marcarNoUtil()
    {
        $this->util = false;
        $this->save();
    }

    /**
     * Scope: Por tipo
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope: Útiles
     */
    public function scopeUtiles($query)
    {
        return $query->where('util', true);
    }
}
