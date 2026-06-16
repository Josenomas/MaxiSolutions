<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    /**
     * Mostrar listado de todos los pagos
     */
    public function index()
    {
        $pagos = Pago::with(['solicitud', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Estadísticas
        $stats = [
            'total_pagos' => Pago::count(),
            'pagos_aprobados' => Pago::where('estado', 'aprobado')->count(),
            'pagos_pendientes' => Pago::where('estado', 'pendiente')->count(),
            'monto_total_aprobado' => Pago::where('estado', 'aprobado')->sum('monto'),
            'monto_webpay' => Pago::where('estado', 'aprobado')->where('metodo_pago', 'webpay')->sum('monto'),
            'monto_flow' => Pago::where('estado', 'aprobado')->where('metodo_pago', 'flow')->sum('monto'),
        ];

        return view('admin.pagos.index', compact('pagos', 'stats'));
    }

    /**
     * Mostrar detalle de un pago
     */
    public function show(Pago $pago)
    {
        $pago->load(['solicitud', 'usuario']);
        return view('admin.pagos.show', compact('pago'));
    }
}
