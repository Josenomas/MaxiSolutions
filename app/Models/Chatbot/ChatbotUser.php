<?php

namespace App\Models\Chatbot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ChatbotUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'chatbot_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'plan',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activo' => 'boolean',
    ];

    public function conversaciones()
    {
        return $this->hasMany(Conversacion::class, 'user_id');
    }

    public function configuracion()
    {
        return $this->hasOne(Configuracion::class, 'user_id');
    }

    public function uso()
    {
        return $this->hasMany(Uso::class, 'user_id');
    }

    public function puedeEnviarMensaje()
    {
        $limites = [
            'gratuito' => 50,
            'basico' => 500,
            'premium' => PHP_INT_MAX,
        ];

        $mensajesHoy = $this->uso()
            ->whereDate('fecha', today())
            ->first();

        $limite = $limites[$this->plan] ?? 50;

        return !$mensajesHoy || $mensajesHoy->mensajes_enviados < $limite;
    }

    public function getLimiteMensajes()
    {
        return match($this->plan) {
            'gratuito' => 50,
            'basico' => 500,
            'premium' => 'Ilimitado',
            default => 50,
        };
    }

    public function getMensajesRestantesHoy()
    {
        if ($this->plan === 'premium') {
            return 'Ilimitado';
        }

        $mensajesHoy = $this->uso()
            ->whereDate('fecha', today())
            ->first();

        $limite = $this->getLimiteMensajes();
        $usados = $mensajesHoy ? $mensajesHoy->mensajes_enviados : 0;

        return max(0, $limite - $usados);
    }
}
