<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotConfiguracion extends Model
{
    use HasFactory;

    protected $table = 'chatbot_configuraciones';

    protected $fillable = ['clave', 'valor'];

    /**
     * Obtener el valor de una configuración
     */
    public static function obtener($clave, $default = null)
    {
        $config = self::where('clave', $clave)->first();
        return $config ? $config->valor : $default;
    }

    /**
     * Establecer el valor de una configuración
     */
    public static function establecer($clave, $valor)
    {
        return self::updateOrCreate(
            ['clave' => $clave],
            ['valor' => $valor]
        );
    }
}
