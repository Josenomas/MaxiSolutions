<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .message-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nuevo Mensaje</h1>
        </div>
        <div class="content">
            <p>Hola,</p>
            <p>Has recibido un nuevo mensaje de <strong>{{ $remitente->name }}</strong>:</p>
            
            <div class="message-box">
                <p>{{ $mensaje->mensaje }}</p>
                <small style="color: #666;">{{ $mensaje->created_at->format('d/m/Y H:i') }}</small>
            </div>

            <a href="{{ url('/mensajes') }}" class="button">Ver y Responder Mensaje</a>

            <p style="margin-top: 30px; color: #666;">
                <small>Este es un correo automático. Por favor no respondas directamente a este email.</small>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MaxiSolutions. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
