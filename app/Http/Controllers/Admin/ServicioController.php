<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::latest()->paginate(10);
        return view('admin.servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('admin.servicios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'categoria' => 'required|in:desarrollo_web,capacitacion,consultoria,mantenimiento,otro',
            'precio_base' => 'nullable|numeric|min:0',
            'duracion_estimada' => 'nullable|string|max:50',
            'destacado' => 'boolean',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $validated['destacado'] = $request->has('destacado');

        Servicio::create($validated);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio creado exitosamente.');
    }

    public function edit(Servicio $servicio)
    {
        return view('admin.servicios.edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'categoria' => 'required|in:desarrollo_web,capacitacion,consultoria,mantenimiento,otro',
            'precio_base' => 'nullable|numeric|min:0',
            'duracion_estimada' => 'nullable|string|max:50',
            'destacado' => 'boolean',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $validated['destacado'] = $request->has('destacado');

        $servicio->update($validated);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio eliminado exitosamente.');
    }
}
