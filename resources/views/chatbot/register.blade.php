<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - HateaChistopher</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{background:linear-gradient(135deg,#0f0c29,#302b63,#24243e);min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:system-ui,-apple-system,sans-serif;color:#fff;padding:40px 0}
        .container{width:100%;max-width:420px;padding:20px}
        .card{background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:40px;box-shadow:0 20px 60px rgba(0,0,0,0.3)}
        h1{font-size:28px;font-weight:700;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:8px;text-align:center}
        p{color:rgba(255,255,255,0.6);font-size:14px;margin:0 0 15px;text-align:center}
        .badge{display:inline-block;background:linear-gradient(135deg,#667eea,#764ba2);padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;margin-bottom:20px}
        .alert{background:rgba(220,53,69,0.15);color:#ff6b6b;border:1px solid rgba(220,53,69,0.3);border-radius:10px;padding:12px;margin-bottom:20px;font-size:14px}
        .alert ul{margin:0;padding-left:20px}
        .form-group{margin-bottom:15px}
        label{color:rgba(255,255,255,0.9);font-weight:500;margin-bottom:8px;font-size:14px;display:block}
        input[type=text],input[type=email],input[type=password],input[type=tel]{width:100%;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);border-radius:10px;padding:12px 16px;color:#fff;font-size:15px}
        input:focus{outline:none;background:rgba(255,255,255,0.12);border-color:#667eea;box-shadow:0 0 0 3px rgba(102,126,234,0.15)}
        input::placeholder{color:rgba(255,255,255,0.4)}
        button{width:100%;background:linear-gradient(135deg,#667eea,#764ba2);border:none;border-radius:10px;padding:14px;font-weight:600;font-size:15px;color:#fff;cursor:pointer;transition:transform 0.2s}
        button:hover{transform:translateY(-2px);box-shadow:0 10px 25px rgba(102,126,234,0.4)}
        .divider{text-align:center;margin:25px 0;position:relative}
        .divider::before{content:'';position:absolute;left:0;top:50%;width:100%;height:1px;background:rgba(255,255,255,0.1)}
        .divider span{background:rgba(255,255,255,0.05);padding:0 15px;position:relative;color:rgba(255,255,255,0.5);font-size:13px}
        .link{color:#667eea;text-decoration:none;font-weight:500;display:block;text-align:center}
        .link:hover{color:#764ba2}
        .back{text-align:center;margin-top:20px}
        .back a{color:rgba(255,255,255,0.6);text-decoration:none;font-size:14px}
        .back a:hover{color:#fff}
        small{color:rgba(255,255,255,0.5)}
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🤖 HateaChistopher</h1>
            <p>Crea tu cuenta y empieza a roastear</p>
            <span class="badge">🎁 Plan Gratuito - 50 roasts/día</span>

            @if ($errors->any())
                <div class="alert">
                    ⚠️ <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('chatbot.register') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Nombre completo</label>
                    <input type="text" id="name" name="name" placeholder="Tu nombre" required autofocus value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="tu@email.com" required value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono <small>(opcional)</small></label>
                    <input type="tel" id="telefono" name="telefono" placeholder="+56 9 1234 5678" value="{{ old('telefono') }}">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repite tu contraseña" required>
                </div>

                <button type="submit">✓ Crear cuenta gratis</button>
            </form>

            <div class="divider"><span>¿Ya tienes cuenta?</span></div>

            <a href="{{ route('chatbot.login') }}" class="link">Iniciar sesión →</a>
        </div>

        <div class="back">
            <a href="{{ route('chatbot.home') }}">← Volver al inicio</a>
        </div>
    </div>
</body>
</html>
