<?php

namespace App\Models\Chatbot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $table = 'chatbot_mensajes';

    protected $fillable = [
        'conversacion_id',
        'role',
        'contenido',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $appends = ['es_usuario'];

    public function conversacion()
    {
        return $this->belongsTo(Conversacion::class, 'conversacion_id');
    }

    // Accessor para compatibilidad con el frontend
    public function getEsUsuarioAttribute()
    {
        return $this->role === 'user';
    }
}
