<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Muestra el marketplace de productos SaaS
     */
    public function index()
    {
        // Obtener solo productos activos
        $productos = Producto::where('activo', true)
            ->with('planes')
            ->orderBy('orden')
            ->get();

        return view('productos.index', compact('productos'));
    }

    /**
     * Redirige al subdominio del producto
     */
    public function acceder($slug)
    {
        $producto = Producto::where('slug', $slug)
            ->where('activo', true)
            ->firstOrFail();

        // Verificar si el usuario tiene acceso (si requiere suscripción)
        if ($producto->requiere_suscripcion && auth()->check()) {
            $organizacion = auth()->user()->organizacion;

            if (!$organizacion || !$organizacion->tieneAcceso($slug)) {
                return redirect()
                    ->route('productos.index')
                    ->with('error', 'No tienes acceso a este producto. Por favor suscríbete primero.');
            }
        }

        // Redirigir al subdominio
        $url = $producto->url_base;

        return redirect()->away($url);
    }
}
