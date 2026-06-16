<?php

namespace App\Http\Controllers;

use App\Mail\NuevaSolicitudNotificacion;
use App\Mail\SolicitudConfirmacion;
use App\Models\Solicitud;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SolicitudController extends Controller
{
    public function create()
    {
        $servicios = Servicio::where('estado', 'activo')->get();
        return view('solicitudes.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'servicio_id' => 'nullable|exists:servicios,id',
            'nombre_cliente' => 'required|string|max:100',
            'email_cliente' => 'required|email|max:150',
            'telefono_cliente' => 'nullable|string|max:20',
            'empresa' => 'nullable|string|max:150',
            'descripcion_proyecto' => 'required|string',
            'presupuesto_estimado' => 'nullable|string|max:50',
        ]);

        $solicitud = Solicitud::create($validated);

        // Enviar email al administrador
        try {
            Mail::to(env('ADMIN_EMAIL', 'admin@maxisolutions.com'))
                ->send(new NuevaSolicitudNotificacion($solicitud));
        } catch (\Exception $e) {
            Log::error('Error enviando email al admin: ' . $e->getMessage());
        }

        // Enviar confirmacion al cliente
        try {
            Mail::to($solicitud->email_cliente)
                ->send(new SolicitudConfirmacion($solicitud));
        } catch (\Exception $e) {
            Log::error('Error enviando confirmacion al cliente: ' . $e->getMessage());
        }

        return redirect()->route('home')->with('success', 'Solicitud enviada con exito! Nos pondremos en contacto contigo pronto.');
    }
}
