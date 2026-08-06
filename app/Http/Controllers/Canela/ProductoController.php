<?php

namespace App\Http\Controllers\Canela;

use App\Http\Controllers\Controller;
use App\Models\Canela\Producto;
use App\Models\Canela\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')
            ->orderBy('nombre')
            ->paginate(20);

        return view('canela.productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('canela.productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoria_id' => 'required|exists:canela_categorias,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_venta' => 'required|numeric|min:0',
            'precio_costo' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'unidad' => 'required|string|max:50',
            'activo' => 'boolean',
        ]);

        Producto::create($validated);

        return redirect()->route('canela.productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('canela.productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'categoria_id' => 'required|exists:canela_categorias,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_venta' => 'required|numeric|min:0',
            'precio_costo' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'unidad' => 'required|string|max:50',
            'activo' => 'boolean',
        ]);

        $producto->update($validated);

        return redirect()->route('canela.productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('canela.productos.index')
            ->with('success', 'Producto eliminado exitosamente');
    }

    // Método para ajustar stock
    public function ajustarStock(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'cantidad' => 'required|integer',
            'tipo' => 'required|in:agregar,quitar',
        ]);

        if ($validated['tipo'] === 'agregar') {
            $producto->stock += $validated['cantidad'];
        } else {
            $producto->stock -= $validated['cantidad'];
        }

        $producto->save();

        return back()->with('success', 'Stock ajustado exitosamente');
    }
}
