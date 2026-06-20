<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - HateaChistopher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --bg-dark: #0f0c29;
            --bg-darker: #0a0818;
        }

        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #fff;
            padding: 40px 0;
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h1 {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .logo p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            margin: 0;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 12px 16px;
            color: #fff;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-weight: 600;
            font-size: 15px;
            width: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .divider span {
            background: rgba(255, 255, 255, 0.05);
            padding: 0 15px;
            position: relative;
            color: rgba(255, 255, 255, 0.5);
            font-size: 13px;
        }

        .text-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .text-link:hover {
            color: #764ba2;
        }

        .alert {
            border-radius: 10px;
            border: none;
            font-size: 14px;
            padding: 12px 16px;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.15);
            color: #ff6b6b;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .back-home {
            text-align: center;
            margin-top: 20px;
        }

        .back-home a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s ease;
        }

        .back-home a:hover {
            color: #fff;
        }

        .plan-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="logo">
                <h1><i class="fas fa-robot"></i> HateaChistopher</h1>
                <p>Crea tu cuenta y empieza a roastear</p>
                <span class="plan-badge">
                    <i class="fas fa-gift"></i> Plan Gratuito - 50 roasts/día
                </span>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul class="mb-0" style="padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('chatbot.register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="name" name="name"
                           placeholder="Tu nombre" required autofocus value="{{ old('name') }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="tu@email.com" required value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono <small class="text-muted">(opcional)</small></label>
                    <input type="text" class="form-control" id="telefono" name="telefono"
                           placeholder="+56 9 1234 5678" value="{{ old('telefono') }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Mínimo 8 caracteres" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                           placeholder="Repite tu contraseña" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Crear cuenta gratis
                </button>
            </form>

            <div class="divider">
                <span>¿Ya tienes cuenta?</span>
            </div>

            <div class="text-center">
                <a href="{{ route('chatbot.login') }}" class="text-link">
                    Iniciar sesión <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="back-home">
            <a href="{{ route('chatbot.home') }}">
                <i class="fas fa-arrow-left"></i> Volver al inicio
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
