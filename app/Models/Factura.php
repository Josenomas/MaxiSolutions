<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizacion_id',
        'suscripcion_id',
        'numero_factura',
        'fecha_emision',
        'fecha_vencimiento',
        'subtotal',
        'iva',
        'total',
        'estado',
        'metodo_pago',
        'pago_id',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class);
    }

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class);
    }

    public function pago()
    {
        return $this->belongsTo(Pago::class);
    }

    /**
     * Marcar como pagada
     */
    public function marcarPagada($pagoId = null)
    {
        $this->estado = 'pagada';
        if ($pagoId) {
            $this->pago_id = $pagoId;
        }
        $this->save();
    }

    /**
     * Verificar si está vencida
     */
    public function estaVencida()
    {
        return $this->estado === 'pendiente' && $this->fecha_vencimiento->isPast();
    }

    /**
     * Generar número de factura
     */
    public static function generarNumeroFactura()
    {
        $ultimo = static::latest('id')->first();
        $numero = $ultimo ? ($ultimo->id + 1) : 1;
        return 'FAC-' . date('Y') . '-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
    }
}
