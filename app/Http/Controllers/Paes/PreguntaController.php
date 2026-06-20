<?php

namespace App\Http\Controllers\Paes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Paes\Materia;
use App\Models\Paes\Tema;
use App\Models\Paes\Pregunta;
use App\Models\Paes\Sesion;
use App\Models\Paes\Respuesta;

class PreguntaController extends Controller
{
    public function iniciarPractica(Request $request)
    {
        $request->validate([
            'materia_id' => 'required|exists:paes_materias,id',
            'tema_id' => 'nullable|exists:paes_temas,id',
            'cantidad' => 'integer|min:5|max:50',
        ]);

        $user = Auth::guard('paes')->user();
        $cantidad = $request->cantidad ?? 10;

        if (!$this->verificarLimitePreguntas($user, $cantidad)) {
            return response()->json([
                'error' => 'Has alcanzado el límite de preguntas de tu plan.'
            ], 403);
        }

        $sesion = Sesion::create([
            'user_id' => $user->id,
            'materia_id' => $request->materia_id,
            'tema_id' => $request->tema_id,
            'tipo' => 'practica',
            'total_preguntas' => $cantidad,
            'fecha_inicio' => now(),
        ]);

        $preguntas = $this->obtenerPreguntas(
            $request->materia_id,
            $request->tema_id,
            $cantidad,
            $user->puedeUsar('ia')
        );

        foreach ($preguntas as $index => $pregunta) {
            Respuesta::create([
                'sesion_id' => $sesion->id,
                'pregunta_id' => $pregunta->id,
                'orden' => $index + 1,
            ]);
        }

        return response()->json([
            'sesion_id' => $sesion->id,
            'preguntas' => $preguntas->load('alternativas'),
        ]);
    }

    public function responder(Request $request)
    {
        $request->validate([
            'sesion_id' => 'required|exists:paes_sesiones,id',
            'pregunta_id' => 'required|exists:paes_preguntas,id',
            'alternativa_id' => 'required|exists:paes_alternativas,id',
        ]);

        $user = Auth::guard('paes')->user();
        $sesion = Sesion::findOrFail($request->sesion_id);

        if ($sesion->user_id !== $user->id) {
            return response()->json(['error' => 'Sesión no válida'], 403);
        }

        $respuesta = Respuesta::where('sesion_id', $request->sesion_id)
            ->where('pregunta_id', $request->pregunta_id)
            ->firstOrFail();

        if ($respuesta->alternativa_id) {
            return response()->json(['error' => 'Ya respondida'], 400);
        }

        $alternativaCorrecta = $respuesta->pregunta->alternativas()
            ->where('es_correcta', true)
            ->first();

        $esCorrecta = $request->alternativa_id == $alternativaCorrecta->id;

        $respuesta->update([
            'alternativa_id' => $request->alternativa_id,
            'es_correcta' => $esCorrecta,
            'tiempo_respuesta' => $request->tiempo ?? null,
        ]);

        return response()->json([
            'correcta' => $esCorrecta,
            'alternativa_correcta_id' => $alternativaCorrecta->id,
            'explicacion' => $respuesta->pregunta->explicacion,
        ]);
    }

    public function finalizarSesion(Request $request, $sesionId)
    {
        $user = Auth::guard('paes')->user();
        $sesion = Sesion::findOrFail($sesionId);

        if ($sesion->user_id !== $user->id) {
            return response()->json(['error' => 'Sesión no válida'], 403);
        }

        $sesion->fecha_fin = now();
        $sesion->calcularResultados();
        $sesion->save();

        return response()->json([
            'sesion' => $sesion,
            'resultados' => [
                'total' => $sesion->total_preguntas,
                'correctas' => $sesion->correctas,
                'incorrectas' => $sesion->incorrectas,
                'porcentaje' => $sesion->porcentaje_acierto,
            ]
        ]);
    }

    private function obtenerPreguntas($materiaId, $temaId, $cantidad, $usarIA)
    {
        $query = Pregunta::where('materia_id', $materiaId);

        if ($temaId) {
            $query->where('tema_id', $temaId);
        }

        if ($usarIA) {
            $query->orderByDesc('generada_ia');
        }

        return $query->with('alternativas')
            ->inRandomOrder()
            ->limit($cantidad)
            ->get();
    }


    public function obtenerTemasPorMateria($materiaId)
    {
        $temas = Tema::where('materia_id', $materiaId)
            ->select('id', 'nombre')
            ->orderBy('orden')
            ->get();

        return response()->json($temas);
    }

    private function verificarLimitePreguntas($user, $cantidad)
    {
        if ($user->plan === 'premium') {
            return true;
        }

        $limite = match($user->plan) {
            'gratuito' => 10,
            'basico' => 100,
            default => 10,
        };

        $preguntasHoy = Respuesta::whereHas('sesion', function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->whereDate('fecha_inicio', today());
        })->count();

        return ($preguntasHoy + $cantidad) <= $limite;
    }
}