<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Portafolio;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $servicios = Servicio::where('estado', 'activo')
            ->orderBy('destacado', 'desc')
            ->get();

        $portafolio = Portafolio::where('estado', 'activo')
            ->where('destacado', true)
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('servicios', 'portafolio'));
    }
}
