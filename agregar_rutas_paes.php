<?php

$file = 'routes/web.php';
$content = file_get_contents($file);

// Agregar import
$content = str_replace(
    'use App\Http\Controllers\ProductoController;',
    'use App\Http\Controllers\ProductoController;
use App\Http\Controllers\Paes\PreguntaController;',
    $content
);

// Agregar rutas API
$insertPoint = '    // Auth PAES (TODO: implementar login/register)';
$rutasAPI = '
    // API de práctica de preguntas
    Route::middleware([\'auth:paes\'])->prefix(\'app/api\')->group(function () {
        Route::post(\'/preguntas/iniciar\', [PreguntaController::class, \'iniciarPractica\'])->name(\'paes.api.preguntas.iniciar\');
        Route::post(\'/preguntas/responder\', [PreguntaController::class, \'responder\'])->name(\'paes.api.preguntas.responder\');
        Route::post(\'/sesion/{sesionId}/finalizar\', [PreguntaController::class, \'finalizarSesion\'])->name(\'paes.api.sesion.finalizar\');
    });

    ' . $insertPoint;

$content = str_replace($insertPoint, $rutasAPI, $content);

file_put_contents($file, $content);
echo "Rutas API agregadas correctamente\n";
