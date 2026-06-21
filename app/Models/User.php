<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo_usuario',
        'admin_role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'usuario_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'usuario_id');
    }

    public function isAdmin()
    {
        return $this->tipo_usuario === 'admin';
    }

    public function isSuperAdmin()
    {
        return $this->admin_role === 'super_admin';
    }

    public function canAccessChatbot()
    {
        return in_array($this->admin_role, ['super_admin', 'admin_chatbot']);
    }

    public function canAccessPaes()
    {
        return in_array($this->admin_role, ['super_admin', 'admin_paes']);
    }

    public function canAccessPrincipal()
    {
        return in_array($this->admin_role, ['super_admin', 'admin_principal']);
    }

    public function hasAdminRole()
    {
        return $this->isAdmin() && !is_null($this->admin_role);
    }
}
