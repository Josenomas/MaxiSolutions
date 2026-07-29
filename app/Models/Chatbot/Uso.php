<?php

namespace App\Models\Chatbot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uso extends Model
{
    use HasFactory;

    protected $table = 'chatbot_uso';

    protected $fillable = [
        'user_id',
        'fecha',
        'mensajes_enviados',
        'tokens_usados',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(ChatbotUser::class, 'user_id');
    }

    public static function registrarMensaje($userId, $tokensUsados = 0)
    {
        $uso = self::firstOrCreate(
            ['user_id' => $userId, 'fecha' => today()],
            ['mensajes_enviados' => 0, 'tokens_usados' => 0]
        );

        $uso->increment('mensajes_enviados');
        $uso->increment('tokens_usados', $tokensUsados);

        return $uso;
    }
}
