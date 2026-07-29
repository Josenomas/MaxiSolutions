<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\Paes\PreguntaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevoMensajeMail;
use App\Mail\CambioEstadoMail;
use App\Mail\PagoRecibidoMail;


// ========================================
// RUTAS SUBDOMINIO CHATBOT
// ========================================
Route::domain('hateachistopher.maxisolutions.cl')->group(function () {
    Route::get('/', [\App\Http\Controllers\Chatbot\ChatbotHomeController::class, 'landing'])->name('chatbot.home');

    Route::middleware(['auth:chatbot'])->prefix('app')->group(function () {
        Route::get('/', [\App\Http\Controllers\Chatbot\ChatbotController::class, 'dashboard'])->name('chatbot.dashboard');
        Route::get('/chat/{conversacionId?}', [\App\Http\Controllers\Chatbot\ChatbotController::class, 'chat'])->name('chatbot.chat');
    });

    Route::middleware(['auth:chatbot'])->prefix('app/api')->group(function () {
        Route::post('/enviar-mensaje', [\App\Http\Controllers\Chatbot\ChatController::class, 'enviarMensaje'])->name('chatbot.api.enviar-mensaje');
        Route::post('/nueva-conversacion', [\App\Http\Controllers\Chatbot\ChatController::class, 'nuevaConversacion'])->name('chatbot.api.nueva-conversacion');
        Route::get('/conversacion/{id}', [\App\Http\Controllers\Chatbot\ChatController::class, 'obtenerConversacion'])->name('chatbot.api.conversacion');
    });

    // Rutas de autenticación
    Route::get('/login', [\App\Http\Controllers\Chatbot\AuthController::class, 'showLogin'])->name('chatbot.login');
    Route::post('/login', [\App\Http\Controllers\Chatbot\AuthController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\Chatbot\AuthController::class, 'showRegister'])->name('chatbot.register');
    Route::post('/register', [\App\Http\Controllers\Chatbot\AuthController::class, 'register']);

    Route::middleware('auth:chatbot')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Chatbot\AuthController::class, 'logout'])->name('chatbot.logout');
    });
});
// ========================================
// RUTAS SUBDOMINIO PAES
// ========================================
Route::domain('paes.maxisolutions.cl')->group(function () {
    // Landing page pública
    Route::get('/', [\App\Http\Controllers\Paes\PaesHomeController::class, 'landing'])->name('paes.home');

    // Rutas autenticadas (dashboard en /app)
    Route::middleware(['auth:paes'])->prefix('app')->group(function () {
        Route::get('/', [\App\Http\Controllers\Paes\PaesController::class, 'dashboard'])->name('paes.dashboard');
        Route::get('/practica', [\App\Http\Controllers\Paes\PaesController::class, 'practica'])->name('paes.practica');
        Route::get('/simulador', [\App\Http\Controllers\Paes\PaesController::class, 'simulador'])->name('paes.simulador');
        Route::get('/estadisticas', [\App\Http\Controllers\Paes\PaesController::class, 'estadisticas'])->name('paes.estadisticas');
    });


    // API de práctica de preguntas
    Route::middleware(['auth:paes'])->prefix('app/api')->group(function () {
        Route::post('/preguntas/iniciar', [PreguntaController::class, 'iniciarPractica'])->name('paes.api.preguntas.iniciar');
        Route::post('/preguntas/responder', [PreguntaController::class, 'responder'])->name('paes.api.preguntas.responder');
        Route::post('/sesion/{sesionId}/finalizar', [PreguntaController::class, 'finalizarSesion'])->name('paes.api.sesion.finalizar');
        Route::get('/materias/{materiaId}/temas', [PreguntaController::class, 'obtenerTemasPorMateria'])->name('paes.api.materias.temas');
    });

    // Rutas de autenticación PAES
    Route::get('/login', [\App\Http\Controllers\Paes\AuthController::class, 'showLogin'])->name('paes.login');
    Route::post('/login', [\App\Http\Controllers\Paes\AuthController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\Paes\AuthController::class, 'showRegister'])->name('paes.register');
    Route::post('/register', [\App\Http\Controllers\Paes\AuthController::class, 'register']);

    Route::middleware('auth:paes')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Paes\AuthController::class, 'logout'])->name('paes.logout');
    });
});

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');

// Productos SaaS
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/producto/{slug}', [ProductoController::class, 'acceder'])->name('producto.acceder');

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

// Cambio de contraseña obligatorio
Route::middleware('auth')->group(function () {
    Route::get('/cambiar-password', function() {
        return view('cambiar-password');
    })->name('cambiar-password');

    Route::post('/cambiar-password', function(\Illuminate\Http\Request $request) {
        $request->validate([
            'password_actual' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!\Hash::check($request->password_actual, auth()->user()->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta']);
        }

        auth()->user()->update([
            'password' => \Hash::make($request->password),
            'debe_cambiar_password' => false
        ]);

        return redirect()->route('dashboard')->with('success', 'Contraseña actualizada exitosamente');
    })->name('cambiar-password.update');
});

// Rutas protegidas con autenticación
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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

    // Mensajes (clientes)
    Route::get('/mensajes', function () {
        $user = auth()->user();
        $admins = \App\Models\User::where('tipo_usuario', 'admin')->get();
        $mensajes = \App\Models\Mensaje::where(function($query) use ($user) {
            $query->where('usuario_id', $user->id)
                  ->orWhere('destinatario_id', $user->id);
        })->with(['usuario', 'destinatario'])->orderBy('created_at', 'asc')->get();
        
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

        // Si no se especifica destinatario, asignar al primer admin
        $destinatarioId = $request->destinatario_id;
        if (!$destinatarioId) {
            $admin = \App\Models\User::where('tipo_usuario', 'admin')->first();
            $destinatarioId = $admin ? $admin->id : null;
        }

        $mensaje = \App\Models\Mensaje::create([
            'usuario_id' => auth()->id(),
            'destinatario_id' => $destinatarioId,
            'mensaje' => $request->mensaje,
            'es_admin' => auth()->user()->isAdmin(),
            'leido' => false
        ]);

        if ($destinatarioId) {
            $destinatario = \App\Models\User::find($destinatarioId);
            if ($destinatario) {
                Mail::to($destinatario->email)->send(new NuevoMensajeMail($mensaje));
            }
        }
        
        return redirect()->route('cliente.mensajes')->with('success', 'Mensaje enviado');
    })->name('cliente.mensajes.store')->middleware('throttle:10,1');

    // Detalle de Solicitud (clientes)
    Route::get('/solicitud/{solicitud}', function (\App\Models\Solicitud $solicitud) {
        if ($solicitud->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta solicitud');
        }

        $solicitud->load(['servicio', 'pagos', 'comentarios.user', 'historial.user']);
        $clienteRegistrado = auth()->user();

        return view('cliente.solicitud-detalle', compact('solicitud', 'clienteRegistrado'));
    })->name('cliente.solicitud.show');
});

// Rutas de Administración (solo para admins)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Gestión de Servicios
    Route::resource('servicios', \App\Http\Controllers\Admin\ServicioController::class);
    
    // Gestión de Solicitudes
    Route::resource('solicitudes', \App\Http\Controllers\Admin\SolicitudController::class)->only(['index', 'show', 'update', 'destroy'])->parameters(['solicitudes' => 'solicitud']);
    Route::post('solicitudes/{solicitud}/comentarios', [\App\Http\Controllers\Admin\SolicitudController::class, 'storeComment'])->name('solicitudes.comentarios.store');

    // Gestión de Pagos
    Route::resource('pagos', \App\Http\Controllers\Admin\PagoController::class)->only(['index', 'show', 'destroy']);
    
    // Gestión de Plantillas
    Route::resource('plantillas', \App\Http\Controllers\Admin\PlantillaController::class);
    
    // Gestión de Logs de Seguridad
    Route::get('security-logs', [\App\Http\Controllers\Admin\SecurityLogController::class, 'index'])->name('security-logs.index');
    Route::get('security-logs/{log}', [\App\Http\Controllers\Admin\SecurityLogController::class, 'show'])->name('security-logs.show');
    Route::get('plantillas/{plantilla}/obtener', [\App\Http\Controllers\Admin\PlantillaController::class, 'obtener'])->name('plantillas.obtener');

    // Gestión de Usuarios
    Route::resource('usuarios', \App\Http\Controllers\Admin\UsuariosController::class)->parameters(['usuarios' => 'usuario']);
    Route::post('usuarios/{usuario}/reset-password', [\App\Http\Controllers\Admin\UsuariosController::class, 'resetPassword'])->name('usuarios.reset-password');

    // Gestión de Mensajes (Admin)
    Route::get('mensajes', function () {
        // Obtener todos los mensajes donde el admin participa
        $mensajes = \App\Models\Mensaje::where('destinatario_id', auth()->id())
            ->orWhere('usuario_id', auth()->id())
            ->with(['usuario', 'destinatario'])
            ->get();

        // Agrupar por cliente
        $clientesMap = [];
        foreach ($mensajes as $mensaje) {
            // Identificar quién es el cliente (el que NO es admin)
            $clienteId = null;
            if ($mensaje->usuario_id === auth()->id()) {
                // El admin envió el mensaje, el cliente es el destinatario
                $clienteId = $mensaje->destinatario_id;
            } else {
                // El cliente envió el mensaje
                $clienteId = $mensaje->usuario_id;
            }

            if (!isset($clientesMap[$clienteId])) {
                $usuario = \App\Models\User::find($clienteId);
                if ($usuario && !$usuario->isAdmin()) {
                    $clientesMap[$clienteId] = [
                        'usuario' => $usuario,
                        'ultimo_mensaje' => $mensaje->created_at,
                        'total_mensajes' => 1,
                        'no_leidos' => ($mensaje->destinatario_id === auth()->id() && !$mensaje->leido) ? 1 : 0
                    ];
                }
            } else {
                $clientesMap[$clienteId]['total_mensajes']++;
                if ($mensaje->created_at > $clientesMap[$clienteId]['ultimo_mensaje']) {
                    $clientesMap[$clienteId]['ultimo_mensaje'] = $mensaje->created_at;
                }
                if ($mensaje->destinatario_id === auth()->id() && !$mensaje->leido) {
                    $clientesMap[$clienteId]['no_leidos']++;
                }
            }
        }

        // Ordenar por último mensaje
        $clientes = collect($clientesMap)->sortByDesc('ultimo_mensaje')->values()->all();

        return view('admin.mensajes.index', compact('clientes'));
    })->name('mensajes.index');

    Route::get('mensajes/{usuario}', function (\App\Models\User $usuario) {
        // FIX: Validar que el destinatario NO sea admin
        if ($usuario->isAdmin()) {
            abort(403, 'No puedes ver mensajes de otro administrador');
        }

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
        // FIX: Validar que el destinatario NO sea admin
        if ($usuario->isAdmin()) {
            abort(403, 'No puedes enviar mensajes a otro administrador');
        }

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
    })->name('mensajes.reply')->middleware('throttle:20,1');

    // ========================================
    // GESTIÓN ADMIN - CHATBOT
    // ========================================
    Route::prefix('chatbot')->name('chatbot.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\Chatbot\ChatbotDashboardController::class, 'index'])->name('dashboard');

        // Gestión de usuarios del chatbot
        Route::resource('usuarios', \App\Http\Controllers\Admin\Chatbot\ChatbotUsuariosController::class)->only(['index', 'show', 'update', 'destroy']);

        // Gestión de conversaciones
        Route::resource('conversaciones', \App\Http\Controllers\Admin\Chatbot\ChatbotConversacionesController::class)->only(['index', 'show', 'destroy']);

        // Configuración global del chatbot
        Route::get('/configuracion', [\App\Http\Controllers\Admin\Chatbot\ChatbotConfiguracionController::class, 'index'])->name('configuracion');
        Route::put('/configuracion', [\App\Http\Controllers\Admin\Chatbot\ChatbotConfiguracionController::class, 'update'])->name('configuracion.update');
    });

    // ========================================
    // GESTIÓN ADMIN - PAES (pendiente de implementar)
    // ========================================
    Route::prefix('paes')->name('paes.')->group(function () {
        // TODO: Implementar rutas admin PAES
    });

});

require __DIR__.'/auth.php';


// Rutas de Pago (requieren autenticación y ownership)
Route::middleware('auth')->group(function () {
    Route::get('/pago/checkout/{solicitud}', function(\App\Models\Solicitud $solicitud) {
        // Verificar ownership: solo el dueño de la solicitud puede pagar
        if ($solicitud->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para pagar esta solicitud');
        }
        return app(\App\Http\Controllers\WebpayController::class)->checkout($solicitud->id);
    })->name('pago.checkout');

    Route::post('/pago/webpay/{solicitud}', function(\Illuminate\Http\Request $request, \App\Models\Solicitud $solicitud) {
        // Verificar ownership: solo el dueño de la solicitud puede pagar
        if ($solicitud->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para pagar esta solicitud');
        }
        return app(\App\Http\Controllers\WebpayController::class)->pay($request, $solicitud->id);
    })->name('webpay.pay');

    Route::post('/pago/flow/{solicitud}', function(\Illuminate\Http\Request $request, \App\Models\Solicitud $solicitud) {
        // Verificar ownership: solo el dueño de la solicitud puede pagar
        if ($solicitud->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para pagar esta solicitud');
        }
        return app(\App\Http\Controllers\FlowController::class)->pay($request, $solicitud->id);
    })->name('flow.pay');
});

// Callbacks de Pasarelas (públicos por necesidad)
Route::post('/pago/webpay/return', [\App\Http\Controllers\WebpayController::class, 'return'])->name('webpay.return');
Route::get('/pago/webpay/return', [\App\Http\Controllers\WebpayController::class, 'return']);


// Ruta de prueba para Flow (sin middleware, sin nada)
Route::match(['get', 'post'], '/flow-test', function(\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Log::info('Flow test endpoint llamado', [
        'method' => $request->method(),
        'all' => $request->all(),
        'headers' => $request->headers->all()
    ]);
    return response('OK', 200);
});

// Debug completo de Flow
Route::any('/pago/flow/debug', function(\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Log::info('=== FLOW DEBUG COMPLETO ===', [
        'method' => $request->method(),
        'url_completa' => $request->fullUrl(),
        'path' => $request->path(),
        'query_string' => $request->getQueryString(),
        'query_params' => $request->query(),
        'post_params' => $request->post(),
        'all_input' => $request->all(),
        'headers' => $request->headers->all(),
    ]);
    return response('DEBUG OK - Token recibido: ' . ($request->input('token') ?? 'NO RECIBIDO'), 200);
});

Route::match(['get', 'post'], '/pago/flow/confirm', [\App\Http\Controllers\FlowController::class, 'confirm'])->name('flow.confirm');
Route::match(['get', 'post'], '/pago/flow/return', [\App\Http\Controllers\FlowController::class, 'return'])->name('flow.return');

// Boleta Electrónica
Route::get('/pago/boleta/{pago}', [\App\Http\Controllers\BoletaController::class, 'generarBoleta'])->middleware('auth')->name('pago.boleta');
Route::get('/boleta/{pago}', [\App\Http\Controllers\BoletaController::class, 'generarBoletaPublica'])->name('pago.boleta.public');
