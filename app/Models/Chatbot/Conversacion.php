<?php

namespace App\Models\Chatbot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversacion extends Model
{
    use HasFactory;

    protected $table = 'chatbot_conversaciones';

    protected $fillable = [
        'user_id',
        'titulo',
        'activa',
        'ultima_actividad',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'ultima_actividad' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(ChatbotUser::class, 'user_id');
    }

    public function mensajes()
    {
        return $this->hasMany(Mensaje::class, 'conversacion_id');
    }

    public function actualizarActividad()
    {
        $this->update(['ultima_actividad' => now()]);
    }
}
