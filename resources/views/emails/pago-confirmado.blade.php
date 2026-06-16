<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .success-icon { font-size: 60px; color: #28a745; text-align: center; margin: 20px 0; }
        .info-box { background: white; padding: 20px; margin: 15px 0; border-left: 4px solid #28a745; border-radius: 5px; }
        .amount { font-size: 36px; color: #28a745; font-weight: bold; text-align: center; margin: 20px 0; }
        .footer { text-align: center; margin-top: 20px; color: #888; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pago Confirmado</h1>
            <p>MaxiSolutions</p>
        </div>
        <div class="content">
            <div class="success-icon">✓</div>
            <p>Hola <strong>{{ $pago->solicitud->nombre_cliente }}</strong>,</p>
            <p>Tu pago ha sido procesado exitosamente.</p>
            
            <div class="amount">${{ number_format($pago->monto, 0, ',', '.') }} CLP</div>
            
            <div class="info-box">
                <h3>Detalles del Pago</h3>
                <p><strong>ID de Transaccion:</strong> {{ $pago->id }}</p>
                <p><strong>Orden de Compra:</strong> {{ $pago->buy_order }}</p>
                <p><strong>Metodo de Pago:</strong> {{ strtoupper($pago->metodo_pago) }}</p>
                <p><strong>Estado:</strong> <span style="color: #28a745;">Aprobado</span></p>
                <p><strong>Fecha:</strong> {{ $pago->created_at->format('d/m/Y H:i') }}</p>
                @if($pago->referencia_pago)
                <p><strong>Referencia:</strong> {{ $pago->referencia_pago }}</p>
                @endif
            </div>
            
            <div class="info-box">
                <h3>Solicitud Relacionada</h3>
                <p><strong>Numero de Solicitud:</strong> #{{ $pago->solicitud->id }}</p>
                <p><strong>Servicio:</strong> {{ $pago->solicitud->servicio->nombre ?? 'Servicio personalizado' }}</p>
            </div>
            
            <p>Gracias por tu pago. Nuestro equipo continuara trabajando en tu proyecto.</p>
            <p>Si tienes alguna pregunta sobre este pago, no dudes en contactarnos.</p>
        </div>
        <div class="footer">
            <p>MaxiSolutions - Soluciones Tecnologicas</p>
            <p>Email: contacto@maxisolutions.com | Tel: +56 9 1234 5678</p>
        </div>
    </div>
</body>
</html>