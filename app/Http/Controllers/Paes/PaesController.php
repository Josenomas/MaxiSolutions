<?php

namespace App\Http\Controllers\Paes;

use App\Http\Controllers\Controller;
use App\Models\Paes\Materia;
use App\Models\Paes\Sesion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard principal de PAES
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Obtener todas las materias
        $materias = Materia::with(['temas', 'progresos' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        // Estadísticas globales del usuario
        $sesionesCompletadas = Sesion::where('user_id', $user->id)
            ->where('estado', 'completada')
            ->count();

        $ultimaSesion = Sesion::where('user_id', $user->id)
            ->where('estado', 'completada')
            ->latest('fecha_fin')
            ->first();

        // Progreso por materia
        $progresoMaterias = [];
        foreach ($materias as $materia) {
            $progreso = $materia->progresoUsuario($user->id);
            $progresoMaterias[$materia->id] = [
                'nombre' => $materia->nombre,
                'total_preguntas' => $progreso ? $progreso->sum('preguntas_realizadas') : 0,
                'porcentaje_acierto' => $progreso ? $progreso->avg('porcentaje_acierto') : 0,
                'racha_actual' => $progreso ? $progreso->max('racha_actual') : 0,
            ];
        }

        return view('paes.dashboard', compact(
            'materias',
            'sesionesCompletadas',
            'ultimaSesion',
            'progresoMaterias'
        ));
    }

    /**
     * Página de práctica
     */
    public function practica()
    {
        $materias = Materia::with('temas')->get();

        return view('paes.practica', compact('materias'));
    }

    /**
     * Simulador de examen
     */
    public function simulador()
    {
        $materias = Materia::all();

        return view('paes.simulador', compact('materias'));
    }

    /**
     * Estadísticas del usuario
     */
    public function estadisticas()
    {
        $user = Auth::user();

        $materias = Materia::with(['progresos' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        $sesiones = Sesion::where('user_id', $user->id)
            ->where('estado', 'completada')
            ->orderBy('fecha_fin', 'desc')
            ->take(20)
            ->get();

        return view('paes.estadisticas', compact('materias', 'sesiones'));
    }
}
