<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SolicitudController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevoMensajeMail;
use App\Mail\CambioEstadoMail;
use App\Mail\PagoRecibidoMail;

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');

// Solicitudes/Cotizaciones
Route::get('/solicitud/crear', [SolicitudController::class, 'create'])->name('solicitud.create');
Route::post('/solicitud', [SolicitudController::class, 'store'])->name('solicitud.store');

// Dashboard (requiere autenticación)
Route::get('/dashboard', function () {
    // Redirigir admins al dashboard de admin
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    // Dashboard para clientes
    $user = auth()->user();
    $solicitudes = $user->solicitudes()->with(['servicio', 'pagos'])->latest()->get();

    $stats = [
        'total_solicitudes' => $solicitudes->count(),
        'solicitudes_pendientes' => $solicitudes->whereIn('estado', ['pendiente', 'en_revision'])->count(),
        'solicitudes_completadas' => $solicitudes->where('estado', 'completada')->count(),
        'solicitudes_en_proceso' => $solicitudes->whereIn('estado', ['aceptada', 'en_desarrollo'])->count(),
        'total_pagado' => $solicitudes->flatMap->pagos->where('estado', 'completado')->sum('monto')
    ];

    return view('dashboard', compact('solicitudes', 'stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas con autenticación
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
    // Mis Pagos (clientes)
    // Mis Pagos (clientes)
    Route::get('/mis-pagos', function () {
        $user = auth()->user();
        $solicitudes = $user->solicitudes()->with(['servicio', 'pagos'])->latest()->get();
        $totalDeuda = 0;
        $totalPagado = 0;
        foreach ($solicitudes as $solicitud) {
            if ($solicitud->monto_cotizado) {
                $totalDeuda += $solicitud->monto_cotizado;
            }
            $totalPagado += $solicitud->pagos->where('estado', 'completado')->sum('monto');
        }
        $saldoPendiente = $totalDeuda - $totalPagado;
        return view('cliente.pagos', compact('solicitudes', 'totalDeuda', 'totalPagado', 'saldoPendiente'));
    })->name('cliente.pagos');

// Rutas de Administración (solo para admins)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Gestión de Servicios
    Route::resource('servicios', \App\Http\Controllers\Admin\ServicioController::class);
    
    // Gestión de Solicitudes
    Route::resource('solicitudes', \App\Http\Controllers\Admin\SolicitudController::class)->only(['index', 'show', 'update', 'destroy'])->parameters(['solicitudes' => 'solicitud']);
    Route::post('solicitudes/{solicitud}/comentarios', [\App\Http\Controllers\Admin\SolicitudController::class, 'storeComment'])->name('solicitudes.comentarios.store');

    // Gestión de Pagos
    Route::resource('pagos', \App\Http\Controllers\Admin\PagoController::class)->only(['index', 'show']);
    
    // Gestión de Plantillas
    Route::resource('plantillas', \App\Http\Controllers\Admin\PlantillaController::class);
    Route::get('plantillas/{plantilla}/obtener', [\App\Http\Controllers\Admin\PlantillaController::class, 'obtener'])->name('plantillas.obtener');

    // Gestión de Mensajes (Admin)
    Route::get('mensajes', function () {
        $conversaciones = \App\Models\Mensaje::with(['usuario', 'destinatario'])
            ->select('usuario_id')
            ->selectRaw('MAX(created_at) as ultimo_mensaje')
            ->selectRaw('COUNT(*) as total_mensajes')
            ->selectRaw('SUM(CASE WHEN leido = 0 AND destinatario_id = ? THEN 1 ELSE 0 END) as no_leidos', [auth()->id()])
            ->where(function($query) {
                $query->where('destinatario_id', auth()->id())
                      ->orWhere('usuario_id', auth()->id());
            })
            ->groupBy('usuario_id')
            ->orderBy('ultimo_mensaje', 'desc')
            ->get();

        $clientes = [];
        foreach ($conversaciones as $conv) {
            $usuario = \App\Models\User::find($conv->usuario_id);
            if ($usuario && !$usuario->is_admin) {
                $clientes[] = [
                    'usuario' => $usuario,
                    'ultimo_mensaje' => $conv->ultimo_mensaje,
                    'total_mensajes' => $conv->total_mensajes,
                    'no_leidos' => $conv->no_leidos
                ];
            }
        }

        return view('admin.mensajes.index', compact('clientes'));
    })->name('mensajes.index');

    Route::get('mensajes/{usuario}', function (\App\Models\User $usuario) {
        $mensajes = \App\Models\Mensaje::where(function($query) use ($usuario) {
            $query->where('usuario_id', $usuario->id)
                  ->where('destinatario_id', auth()->id());
        })->orWhere(function($query) use ($usuario) {
            $query->where('usuario_id', auth()->id())
                  ->where('destinatario_id', $usuario->id);
        })->with(['usuario', 'destinatario'])
          ->orderBy('created_at', 'asc')
          ->get();

        \App\Models\Mensaje::where('usuario_id', $usuario->id)
            ->where('destinatario_id', auth()->id())
            ->where('leido', false)
            ->update(['leido' => true]);

        return view('admin.mensajes.show', compact('usuario', 'mensajes'));
    })->name('mensajes.show');

    Route::post('mensajes/{usuario}', function (\Illuminate\Http\Request $request, \App\Models\User $usuario) {
        $request->validate([
            'mensaje' => 'required|string|max:1000'
        ]);

        $mensaje = \App\Models\Mensaje::create([
            'usuario_id' => auth()->id(),
            'destinatario_id' => $usuario->id,
            'mensaje' => $request->mensaje,
            'es_admin' => true,
            'leido' => false
        ]);

        // Enviar notificación por email
        Mail::to($usuario->email)->send(new NuevoMensajeMail($mensaje));

        return redirect()->route('admin.mensajes.show', $usuario)->with('success', 'Mensaje enviado');
    })->name('mensajes.reply');

});

require __DIR__.'/auth.php';

// Rutas de Pago
Route::get('/pago/checkout/{solicitud}', [\App\Http\Controllers\WebpayController::class, 'checkout'])->name('pago.checkout');

// Rutas de Pago con Webpay
Route::post('/pago/webpay/{solicitud}', [\App\Http\Controllers\WebpayController::class, 'pay'])->name('webpay.pay');
Route::post('/pago/webpay/return', [\App\Http\Controllers\WebpayController::class, 'return'])->name('webpay.return');
Route::get('/pago/webpay/return', [\App\Http\Controllers\WebpayController::class, 'return']);

// Rutas de Pago con Flow
Route::post('/pago/flow/{solicitud}', [\App\Http\Controllers\FlowController::class, 'pay'])->name('flow.pay');
Route::post('/pago/flow/confirm', [\App\Http\Controllers\FlowController::class, 'confirm'])->name('flow.confirm');
Route::get('/pago/flow/return', [\App\Http\Controllers\FlowController::class, 'return'])->name('flow.return');

    // Mensajes (clientes)
    Route::get('/mensajes', function () {
        $user = auth()->user();
        $admins = \App\Models\User::where('is_admin', true)->get();
        $mensajes = \App\Models\Mensaje::where(function($query) use ($user) {
            $query->where('usuario_id', $user->id)
                  ->orWhere('destinatario_id', $user->id);
        })->with(['usuario', 'destinatario'])->orderBy('created_at', 'asc')->get();
        
        // Marcar mensajes como leídos
        \App\Models\Mensaje::where('destinatario_id', $user->id)
            ->where('leido', false)
            ->update(['leido' => true]);
        
        return view('cliente.mensajes', compact('mensajes', 'admins'));
    })->name('cliente.mensajes');

    Route::post('/mensajes', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'mensaje' => 'required|string|max:1000',
            'destinatario_id' => 'nullable|exists:users,id'
        ]);
        
        $mensaje = \App\Models\Mensaje::create([
            'usuario_id' => auth()->id(),
            'destinatario_id' => $request->destinatario_id,
            'mensaje' => $request->mensaje,
            'es_admin' => auth()->user()->is_admin ?? false,
            'leido' => false
        ]);
        
        // Enviar notificación por email al destinatario
        if ($request->destinatario_id) {
            $destinatario = \App\Models\User::find($request->destinatario_id);
            if ($destinatario) {
                Mail::to($destinatario->email)->send(new NuevoMensajeMail($mensaje));
            }
        }
        
        return redirect()->route('cliente.mensajes')->with('success', 'Mensaje enviado');
    })->name('cliente.mensajes.store');

    // Detalle de Solicitud (clientes)
    Route::get('/solicitud/{solicitud}', function (\App\Models\Solicitud $solicitud) {
        // Verificar que la solicitud pertenezca al usuario
        if ($solicitud->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta solicitud');
        }
        
        $solicitud->load(['servicio', 'pagos', 'comentarios.user', 'historial.user']);
        
        return view('cliente.solicitud-detalle', compact('solicitud'));
    })->name('cliente.solicitud.show');
