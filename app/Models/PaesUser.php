<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PaesUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'paes_users';

    protected $guard = 'paes';

    protected $fillable = [
        'name',
        'email',
        'password',
        'rut',
        'telefono',
        'fecha_nacimiento',
        'plan',
        'plan_expira',
        'organizacion_id',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'fecha_nacimiento' => 'date',
        'plan_expira' => 'datetime',
        'activo' => 'boolean',
    ];

    /**
     * Relación con organización (multi-tenant)
     */
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class);
    }

    /**
     * Verificar si el plan está activo
     */
    public function planActivo(): bool
    {
        if ($this->plan === 'gratuito') {
            return true;
        }

        return $this->plan_expira && $this->plan_expira->isFuture();
    }

    /**
     * Verificar si puede usar una funcionalidad según su plan
     */
    public function puedeUsar(string $funcionalidad): bool
    {
        if (!$this->planActivo()) {
            return false;
        }

        $limites = [
            'gratuito' => ['preguntas_dia' => 10, 'ia' => false],
            'basico' => ['preguntas_dia' => 100, 'ia' => true, 'tutor_dia' => 20],
            'premium' => ['preguntas_dia' => -1, 'ia' => true, 'tutor_dia' => -1],
            'institucional' => ['preguntas_dia' => -1, 'ia' => true, 'tutor_dia' => -1],
        ];

        return $limites[$this->plan][$funcionalidad] ?? false;
    }
}
