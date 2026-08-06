<?php

namespace App\Models\Canela;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'canela_productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio_venta',
        'precio_costo',
        'stock',
        'stock_minimo',
        'unidad',
        'activo',
    ];

    protected $casts = [
        'precio_venta' => 'decimal:2',
        'precio_costo' => 'decimal:2',
        'stock' => 'integer',
        'stock_minimo' => 'integer',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function ventaDetalles()
    {
        return $this->hasMany(VentaDetalle::class, 'producto_id');
    }

    // Accesor para verificar stock bajo
    public function getStockBajoAttribute()
    {
        return $this->stock <= $this->stock_minimo;
    }

    // Accesor para margen de ganancia
    public function getMargenAttribute()
    {
        if (!$this->precio_costo || $this->precio_costo == 0) {
            return 0;
        }
        return (($this->precio_venta - $this->precio_costo) / $this->precio_costo) * 100;
    }
}
