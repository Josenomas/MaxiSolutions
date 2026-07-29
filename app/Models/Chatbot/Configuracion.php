<?php

namespace App\Models\Chatbot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'chatbot_configuraciones';

    protected $fillable = [
        'user_id',
        'modelo',
        'temperatura',
        'max_tokens',
        'personalidad',
    ];

    public function user()
    {
        return $this->belongsTo(ChatbotUser::class, 'user_id');
    }
}
