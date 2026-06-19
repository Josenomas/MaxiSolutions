<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsoProducto extends Model
{
    use HasFactory;

    protected $table = 'uso_producto';

    protected $fillable = [
        'organizacion_id',
        'producto_id',
        'user_id',
        'metrica',
        'cantidad',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtener uso del día
     */
    public static function usoDia($organizacionId, $productoId, $metrica, $fecha = null)
    {
        return static::where('organizacion_id', $organizacionId)
            ->where('producto_id', $productoId)
            ->where('metrica', $metrica)
            ->whereDate('fecha', $fecha ?? today())
            ->sum('cantidad');
    }

    /**
     * Obtener uso del mes
     */
    public static function usoMes($organizacionId, $productoId, $metrica, $mes = null, $anio = null)
    {
        $query = static::where('organizacion_id', $organizacionId)
            ->where('producto_id', $productoId)
            ->where('metrica', $metrica);

        if ($mes && $anio) {
            $query->whereYear('fecha', $anio)
                  ->whereMonth('fecha', $mes);
        } else {
            $query->whereYear('fecha', now()->year)
                  ->whereMonth('fecha', now()->month);
        }

        return $query->sum('cantidad');
    }
}
