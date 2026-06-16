<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .info-box { background: white; padding: 20px; margin: 15px 0; border-left: 4px solid #667eea; border-radius: 5px; }
        .footer { text-align: center; margin-top: 20px; color: #888; font-size: 12px; }
        .btn { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nueva Solicitud de Cotizacion</h1>
            <p>MaxiSolutions</p>
        </div>
        <div class="content">
            <p>Hola Administrador,</p>
            <p>Has recibido una nueva solicitud de cotizacion:</p>
            
            <div class="info-box">
                <h3>Informacion del Cliente</h3>
                <p><strong>Nombre:</strong> {{ $solicitud->nombre_cliente }}</p>
                <p><strong>Email:</strong> {{ $solicitud->email_cliente }}</p>
                <p><strong>Telefono:</strong> {{ $solicitud->telefono_cliente ?? 'No proporcionado' }}</p>
                <p><strong>Empresa:</strong> {{ $solicitud->empresa ?? 'No proporcionado' }}</p>
            </div>
            
            <div class="info-box">
                <h3>Detalles del Proyecto</h3>
                <p><strong>Servicio:</strong> {{ $solicitud->servicio->nombre ?? 'No especificado' }}</p>
                <p><strong>Presupuesto Estimado:</strong> {{ $solicitud->presupuesto_estimado ?? 'No especificado' }}</p>
                <p><strong>Descripcion:</strong></p>
                <p>{{ $solicitud->descripcion_proyecto }}</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('/admin/solicitudes/' . $solicitud->id) }}" class="btn">Ver Solicitud en el Panel</a>
            </div>
        </div>
        <div class="footer">
            <p>MaxiSolutions - Sistema de Gestion</p>
            <p>Este es un email automatico, por favor no responder.</p>
        </div>
    </div>
</body>
</html>