<?php

namespace App\Http\Controllers\Paes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaesHomeController extends Controller
{
    public function landing()
    {
        // Por ahora devolvemos arrays vacíos para evitar errores
        // TODO: Personalizar la landing con contenido PAES
        $servicios = [];
        $productos = [];
        
        return view('paes.landing', compact('servicios', 'productos'));
    }
}
