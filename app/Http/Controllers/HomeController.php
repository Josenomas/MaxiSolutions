<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Portafolio;
use App\Models\Producto;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $servicios = Servicio::where('estado', 'activo')
            ->orderBy('destacado', 'desc')
            ->get();

        // Obtener productos SaaS activos en lugar de portafolio tradicional
        $productos = Producto::where('activo', true)
            ->with('planes')
            ->orderBy('orden')
            ->get();

        return view('home', compact('servicios', 'productos'));
    }
}
