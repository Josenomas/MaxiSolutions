<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Estado</title>
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
        .status-box {
            background: #f8f9fa;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            margin: 10px;
        }
        .badge-pendiente { background: #ffc107; color: #000; }
        .badge-en_revision { background: #17a2b8; color: white; }
        .badge-aceptada { background: #28a745; color: white; }
        .badge-rechazada { background: #dc3545; color: white; }
        .badge-en_desarrollo { background: #007bff; color: white; }
        .badge-completada { background: #6c757d; color: white; }
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
            <h1>Actualización de Estado</h1>
        </div>
        <div class="content">
            <p>Hola <strong>{{ $solicitud->usuario->name }}</strong>,</p>
            <p>Te informamos que el estado de tu solicitud <strong>#{{ $solicitud->id }}</strong> ha sido actualizado.</p>
            
            <div class="status-box">
                <p style="margin-bottom: 10px;">Estado anterior:</p>
                <span class="status-badge badge-{{ $estadoAnterior }}">{{ ucfirst(str_replace('_', ' ', $estadoAnterior)) }}</span>
                
                <p style="margin: 20px 0 10px 0;">&#8595;</p>
                
                <p style="margin-bottom: 10px;">Estado nuevo:</p>
                <span class="status-badge badge-{{ $estadoNuevo }}">{{ ucfirst(str_replace('_', ' ', $estadoNuevo)) }}</span>
            </div>

            @if($solicitud->servicio)
                <p><strong>Servicio:</strong> {{ $solicitud->servicio->nombre }}</p>
            @endif

            <a href="{{ url('/solicitud/' . $solicitud->id) }}" class="button">Ver Detalles de la Solicitud</a>

            <p style="margin-top: 30px; color: #666;">
                <small>Si tienes alguna pregunta, no dudes en contactarnos a través de nuestro sistema de mensajes.</small>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MaxiSolutions. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
