<?php

$content = file_get_contents('web.php');

// Encontrar donde termina el grupo de auth actual (línea 46 aproximadamente)
// y mover las rutas de cliente dentro del grupo

$pattern = '/Route::middleware\(\'auth\'\)->group\(function \(\) \{[^}]*\}\);/s';

preg_match($pattern, $content, $matches);

if ($matches) {
    $oldAuthGroup = $matches[0];
    
    // Nuevo grupo con todas las rutas de cliente incluidas
    $newAuthGroup = "// Rutas protegidas con autenticación
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mis Pagos (clientes)
    Route::get('/mis-pagos', function () {
        \$user = auth()->user();
        \$solicitudes = \$user->solicitudes()->with(['servicio', 'pagos'])->latest()->get();
        \$totalDeuda = 0;
        \$totalPagado = 0;
        foreach (\$solicitudes as \$solicitud) {
            if (\$solicitud->monto_cotizado) {
                \$totalDeuda += \$solicitud->monto_cotizado;
            }
            \$totalPagado += \$solicitud->pagos->where('estado', 'completado')->sum('monto');
        }
        \$saldoPendiente = \$totalDeuda - \$totalPagado;
        return view('cliente.pagos', compact('solicitudes', 'totalDeuda', 'totalPagado', 'saldoPendiente'));
    })->name('cliente.pagos');

    // Mensajes (clientes)
    Route::get('/mensajes', function () {
        \$user = auth()->user();
        \$admins = \App\Models\User::where('tipo_usuario', 'admin')->get();
        \$mensajes = \App\Models\Mensaje::where(function(\$query) use (\$user) {
            \$query->where('usuario_id', \$user->id)
                  ->orWhere('destinatario_id', \$user->id);
        })->with(['usuario', 'destinatario'])->orderBy('created_at', 'asc')->get();
        
        // Marcar mensajes como leídos
        \App\Models\Mensaje::where('destinatario_id', \$user->id)
            ->where('leido', false)
            ->update(['leido' => true]);
        
        return view('cliente.mensajes', compact('mensajes', 'admins'));
    })->name('cliente.mensajes');

    Route::post('/mensajes', function (\Illuminate\Http\Request \$request) {
        \$request->validate([
            'mensaje' => 'required|string|max:1000',
            'destinatario_id' => 'nullable|exists:users,id'
        ]);
        
        \$mensaje = \App\Models\Mensaje::create([
            'usuario_id' => auth()->id(),
            'destinatario_id' => \$request->destinatario_id,
            'mensaje' => \$request->mensaje,
            'es_admin' => auth()->user()->isAdmin(),
            'leido' => false
        ]);
        
        // Enviar notificación por email al destinatario
        if (\$request->destinatario_id) {
            \$destinatario = \App\Models\User::find(\$request->destinatario_id);
            if (\$destinatario) {
                Mail::to(\$destinatario->email)->send(new NuevoMensajeMail(\$mensaje));
            }
        }
        
        return redirect()->route('cliente.mensajes')->with('success', 'Mensaje enviado');
    })->name('cliente.mensajes.store')->middleware('throttle:10,1');

    // Detalle de Solicitud (clientes)
    Route::get('/solicitud/{solicitud}', function (\App\Models\Solicitud \$solicitud) {
        // Verificar que la solicitud pertenezca al usuario
        if (\$solicitud->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta solicitud');
        }
        
        \$solicitud->load(['servicio', 'pagos', 'comentarios.user', 'historial.user']);
        
        return view('cliente.solicitud-detalle', compact('solicitud'));
    })->name('cliente.solicitud.show');
});";

    // Reemplazar el grupo antiguo
    $content = str_replace($oldAuthGroup, $newAuthGroup, $content);
    
    // Eliminar las rutas duplicadas que estaban fuera del grupo
    // Primero eliminar la ruta de mis-pagos suelta
    $content = preg_replace('/\s+\/\/ Mis Pagos \(clientes\)\s+Route::get\(\'\/mis-pagos\'[^;]+;/', '', $content);
    
    // Eliminar las rutas de mensajes sueltas
    $content = preg_replace('/\s+\/\/ Mensajes \(clientes\)[\s\S]*?Route::post\(\'\/mensajes\'[^}]+}\)->name\(\'cliente\.mensajes\.store\'\);/', '', $content);
    
    // Eliminar la ruta de solicitud detalle suelta
    $content = preg_replace('/\s+\/\/ Detalle de Solicitud \(clientes\)[\s\S]*?Route::get\(\'\/solicitud\/\{solicitud\}\'[^}]+}\)->name\(\'cliente\.solicitud\.show\'\);/', '', $content);
    
    file_put_contents('web.php', $content);
    echo "Routes fixed successfully\n";
} else {
    echo "Could not find auth group\n";
}
