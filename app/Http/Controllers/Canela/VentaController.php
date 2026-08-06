<?php

namespace App\Http\Controllers\Canela;

use App\Http\Controllers\Controller;
use App\Models\Canela\Venta;
use App\Models\Canela\VentaDetalle;
use App\Models\Canela\Producto;
use App\Models\Canela\Cliente;
use App\Models\Canela\Deuda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with(['cliente', 'usuario'])
            ->orderBy('fecha_venta', 'desc')
            ->paginate(20);

        return view('canela.ventas.index', compact('ventas'));
    }

    public function create()
    {
        $productos = Producto::where('activo', true)
            ->where('stock', '>', 0)
            ->with('categoria')
            ->orderBy('nombre')
            ->get();

        $clientes = Cliente::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('canela.ventas.create', compact('productos', 'clientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'nullable|exists:canela_clientes,id',
            'tipo_pago' => 'required|in:efectivo,tarjeta,transferencia,credito',
            'descuento' => 'nullable|numeric|min:0',
            'notas' => 'nullable|string',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:canela_productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calcular totales
            $total = 0;
            foreach ($validated['productos'] as $prod) {
                $total += $prod['cantidad'] * $prod['precio'];
            }

            $descuento = $validated['descuento'] ?? 0;
            $totalFinal = $total - $descuento;

            // Crear venta
            $venta = Venta::create([
                'cliente_id' => $validated['cliente_id'],
                'usuario_id' => auth()->id(),
                'total' => $total,
                'descuento' => $descuento,
                'total_final' => $totalFinal,
                'tipo_pago' => $validated['tipo_pago'],
                'estado' => 'completada',
                'notas' => $validated['notas'],
                'fecha_venta' => Carbon::now(),
            ]);

            // Crear detalles y actualizar stock
            foreach ($validated['productos'] as $prod) {
                $producto = Producto::find($prod['id']);

                // Verificar stock
                if ($producto->stock < $prod['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->nombre}");
                }

                // Crear detalle
                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $prod['cantidad'],
                    'precio_unitario' => $prod['precio'],
                    'subtotal' => $prod['cantidad'] * $prod['precio'],
                ]);

                // Reducir stock
                $producto->decrement('stock', $prod['cantidad']);
            }

            // Si es crédito, crear deuda
            if ($validated['tipo_pago'] === 'credito' && $validated['cliente_id']) {
                Deuda::create([
                    'cliente_id' => $validated['cliente_id'],
                    'venta_id' => $venta->id,
                    'monto_total' => $totalFinal,
                    'monto_pagado' => 0,
                    'monto_pendiente' => $totalFinal,
                    'estado' => 'pendiente',
                ]);
            }

            DB::commit();

            return redirect()->route('canela.ventas.show', $venta)
                ->with('success', 'Venta registrada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al registrar venta: ' . $e->getMessage());
        }
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'usuario', 'detalles.producto', 'deuda']);

        return view('canela.ventas.show', compact('venta'));
    }
}
