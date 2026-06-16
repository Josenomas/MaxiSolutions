<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .success-icon { font-size: 60px; color: #4CAF50; text-align: center; margin: 20px 0; }
        .info-box { background: white; padding: 20px; margin: 15px 0; border-left: 4px solid #4CAF50; border-radius: 5px; }
        .footer { text-align: center; margin-top: 20px; color: #888; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Solicitud Recibida</h1>
            <p>MaxiSolutions</p>
        </div>
        <div class="content">
            <div class="success-icon">✓</div>
            <p>Hola <strong>{{ $solicitud->nombre_cliente }}</strong>,</p>
            <p>Hemos recibido tu solicitud de cotizacion exitosamente.</p>
            
            <div class="info-box">
                <h3>Resumen de tu Solicitud</h3>
                <p><strong>Numero de Solicitud:</strong> #{{ $solicitud->id }}</p>
                <p><strong>Servicio:</strong> {{ $solicitud->servicio->nombre ?? 'Servicio personalizado' }}</p>
                <p><strong>Estado:</strong> <span style="color: #FFA500;">Pendiente de revision</span></p>
            </div>
            
            <p>Nuestro equipo revisara tu solicitud y te contactaremos en las proximas 24-48 horas habiles.</p>
            
            <p>Si tienes alguna pregunta, no dudes en contactarnos respondiendo a este email.</p>
            
            <p><strong>Gracias por confiar en MaxiSolutions!</strong></p>
        </div>
        <div class="footer">
            <p>MaxiSolutions - Soluciones Tecnologicas</p>
            <p>Email: contacto@maxisolutions.com | Tel: +56 9 1234 5678</p>
        </div>
    </div>
</body>
</html>