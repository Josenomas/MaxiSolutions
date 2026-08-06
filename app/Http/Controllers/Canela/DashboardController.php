<?php

namespace App\Http\Controllers\Canela;

use App\Http\Controllers\Controller;
use App\Models\Canela\Producto;
use App\Models\Canela\Venta;
use App\Models\Canela\Cliente;
use App\Models\Canela\Deuda;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas del día
        $hoy = Carbon::today();
        $ventasHoy = Venta::whereDate('fecha_venta', $hoy)
            ->where('estado', 'completada')
            ->sum('total_final');

        $numeroVentasHoy = Venta::whereDate('fecha_venta', $hoy)
            ->where('estado', 'completada')
            ->count();

        // Estadísticas del mes
        $inicioMes = Carbon::now()->startOfMonth();
        $ventasMes = Venta::where('fecha_venta', '>=', $inicioMes)
            ->where('estado', 'completada')
            ->sum('total_final');

        // Deudas pendientes
        $deudasPendientes = Deuda::where('estado', 'pendiente')
            ->sum('monto_pendiente');

        // Productos con stock bajo
        $productosStockBajo = Producto::whereRaw('stock <= stock_minimo')
            ->where('activo', true)
            ->count();

        // Últimas ventas
        $ultimasVentas = Venta::with(['cliente', 'usuario'])
            ->orderBy('fecha_venta', 'desc')
            ->take(10)
            ->get();

        // Productos más vendidos (últimos 30 días)
        $productosMasVendidos = Producto::select('canela_productos.*')
            ->join('canela_venta_detalles', 'canela_productos.id', '=', 'canela_venta_detalles.producto_id')
            ->join('canela_ventas', 'canela_venta_detalles.venta_id', '=', 'canela_ventas.id')
            ->where('canela_ventas.fecha_venta', '>=', Carbon::now()->subDays(30))
            ->where('canela_ventas.estado', 'completada')
            ->selectRaw('canela_productos.*, SUM(canela_venta_detalles.cantidad) as total_vendido')
            ->groupBy('canela_productos.id')
            ->orderBy('total_vendido', 'desc')
            ->take(5)
            ->get();

        return view('canela.dashboard', compact(
            'ventasHoy',
            'numeroVentasHoy',
            'ventasMes',
            'deudasPendientes',
            'productosStockBajo',
            'ultimasVentas',
            'productosMasVendidos'
        ));
    }
}
