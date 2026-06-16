<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirigiendo a Flow...</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .redirect-card {
            background: white;
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
        }
        .spinner {
            width: 60px;
            height: 60px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="redirect-card">
        <h3 class="mb-4">Redirigiendo a Flow</h3>
        <div class="spinner"></div>
        <p class="text-muted">Por favor espera mientras te redirigimos a la plataforma de pago...</p>
        <p class="small text-muted mt-4">Si no eres redirigido automáticamente, <a href="{{ $paymentUrl }}" id="manual-link">haz clic aquí</a>.</p>
    </div>

    <script>
        // Redirigir automáticamente después de 2 segundos
        setTimeout(function() {
            window.location.href = "{{ $paymentUrl }}";
        }, 2000);
    </script>
</body>
</html>
