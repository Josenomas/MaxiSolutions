<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portafolio extends Model
{
    use HasFactory;

    protected $table = 'portafolio';

    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria',
        'imagen_principal',
        'url_proyecto',
        'cliente',
        'fecha_proyecto',
        'tecnologias',
        'destacado',
        'estado'
    ];

    protected $casts = [
        'destacado' => 'boolean',
        'fecha_proyecto' => 'date'
    ];
}
