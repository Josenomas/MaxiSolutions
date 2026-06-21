<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HateaChistopher</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{background:linear-gradient(135deg,#0f0c29,#302b63,#24243e);min-height:100vh;font-family:system-ui,-apple-system,sans-serif;color:#fff}
        .navbar{background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);border-bottom:1px solid rgba(255,255,255,0.1);padding:20px;display:flex;justify-content:space-between;align-items:center}
        .navbar h1{font-size:24px;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .navbar-right{display:flex;gap:15px;align-items:center}
        .user-info{color:rgba(255,255,255,0.8);font-size:14px}
        .btn{padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:500;transition:all 0.2s;display:inline-block;border:none;cursor:pointer;font-size:14px}
        .btn-primary{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff}
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 5px 15px rgba(102,126,234,0.4)}
        .btn-secondary{background:rgba(255,255,255,0.1);color:#fff;border:1px solid rgba(255,255,255,0.2)}
        .btn-secondary:hover{background:rgba(255,255,255,0.15)}
        .container{max-width:1200px;margin:0 auto;padding:40px 20px}
        .welcome{margin-bottom:40px}
        .welcome h2{font-size:32px;margin-bottom:10px}
        .welcome p{color:rgba(255,255,255,0.7);font-size:16px}
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;margin-bottom:40px}
        .stat-card{background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.1);border-radius:15px;padding:25px}
        .stat-card h3{font-size:14px;color:rgba(255,255,255,0.7);margin-bottom:10px;text-transform:uppercase;letter-spacing:1px}
        .stat-card .value{font-size:36px;font-weight:700;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:5px}
        .stat-card .label{font-size:12px;color:rgba(255,255,255,0.5)}
        .section{background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.1);border-radius:15px;padding:30px;margin-bottom:20px}
        .section h3{font-size:20px;margin-bottom:20px;display:flex;align-items:center;gap:10px}
        .conversation{background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:15px;margin-bottom:10px;display:flex;justify-content:space-between;align-items:center;transition:all 0.2s}
        .conversation:hover{background:rgba(255,255,255,0.08);transform:translateX(5px)}
        .conversation-title{font-size:16px;margin-bottom:5px}
        .conversation-date{font-size:12px;color:rgba(255,255,255,0.5)}
        .empty-state{text-align:center;padding:40px;color:rgba(255,255,255,0.5)}
        .empty-state-icon{font-size:48px;margin-bottom:15px;opacity:0.5}
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <h1>🤖 HateaChistopher</h1>
        <div class="navbar-right">
            <span class="user-info">👋 Hola, {{ $user->name }}</span>
            <form method="POST" action="{{ route('chatbot.logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-secondary">Cerrar sesión</button>
            </form>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome">
            <h2>Bienvenido de vuelta, {{ $user->name }}</h2>
            <p>Plan: <strong>{{ ucfirst($user->plan) }}</strong> | Roasts disponibles: <strong>{{ $roastsDisponibles }}</strong></p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>📊 Roasts Disponibles</h3>
                <div class="value">{{ $roastsDisponibles }}</div>
                <div class="label">mensajes restantes hoy</div>
            </div>

            <div class="stat-card">
                <h3>💬 Conversaciones</h3>
                <div class="value">{{ $totalConversaciones }}</div>
                <div class="label">conversaciones totales</div>
            </div>

            <div class="stat-card">
                <h3>📈 Uso Diario</h3>
                <div class="value">{{ $mensajesHoy }}</div>
                <div class="label">mensajes enviados hoy</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section">
            <h3>⚡ Acciones Rápidas</h3>
            <div style="display:flex;gap:15px;flex-wrap:wrap">
                <a href="{{ route('chatbot.chat') }}" class="btn btn-primary">💬 Nuevo Chat</a>
                @if($user->plan === 'gratuito')
                    <a href="#" class="btn btn-secondary">⭐ Upgrade a Premium</a>
                @endif
            </div>
        </div>

        <!-- Recent Conversations -->
        <div class="section">
            <h3>🕒 Conversaciones Recientes</h3>
            @if($conversacionesRecientes->count() > 0)
                @foreach($conversacionesRecientes as $conversacion)
                    <div class="conversation">
                        <div>
                            <div class="conversation-title">{{ $conversacion->titulo ?? 'Conversación sin título' }}</div>
                            <div class="conversation-date">{{ $conversacion->created_at->diffForHumans() }}</div>
                        </div>
                        <a href="{{ route('chatbot.chat', $conversacion->id) }}" class="btn btn-secondary">Ver →</a>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">💬</div>
                    <p>No tienes conversaciones aún</p>
                    <p><a href="{{ route('chatbot.chat') }}" class="btn btn-primary" style="margin-top:15px">Iniciar primera conversación</a></p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
