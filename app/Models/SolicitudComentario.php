<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudComentario extends Model
{
    use HasFactory;

    protected $table = 'solicitud_comentarios';

    protected $fillable = [
        'solicitud_id',
        'user_id',
        'comentario',
        'es_interno'
    ];

    protected $casts = [
        'es_interno' => 'boolean'
    ];

    // Relaciones
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}