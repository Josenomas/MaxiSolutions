<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Recibido</title>
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
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .payment-box {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #28a745;
            margin: 20px 0;
        }
        .detail-row {
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
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
        .success-icon {
            font-size: 50px;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">&#10004;</div>
            <h1>Pago Recibido</h1>
        </div>
        <div class="content">
            <p>Hola <strong>{{ $solicitud->usuario->name }}</strong>,</p>
            <p>Hemos recibido exitosamente tu pago. A continuación los detalles:</p>
            
            <div class="payment-box">
                <div class="detail-row">
                    <strong>Solicitud:</strong> #{{ $solicitud->id }}
                </div>
                @if($solicitud->servicio)
                <div class="detail-row">
                    <strong>Servicio:</strong> {{ $solicitud->servicio->nombre }}
                </div>
                @endif
                <div class="detail-row">
                    <strong>Método de Pago:</strong> {{ ucfirst($pago->metodo_pago) }}
                </div>
                <div class="detail-row">
                    <strong>Estado:</strong> {{ ucfirst($pago->estado) }}
                </div>
                <div class="detail-row">
                    <strong>Fecha:</strong> {{ $pago->created_at->format('d/m/Y H:i') }}
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <p style="margin: 5px 0; color: #666;">Monto Pagado</p>
                    <div class="amount">${{ number_format($pago->monto, 0, ',', '.') }}</div>
                </div>
            </div>

            @if($pago->transaction_id)
                <p><small><strong>ID de Transacción:</strong> {{ $pago->transaction_id }}</small></p>
            @endif

            <a href="{{ url('/solicitud/' . $solicitud->id) }}" class="button">Ver Detalles de la Solicitud</a>

            <p style="margin-top: 30px; color: #666;">
                <small>Gracias por tu pago. Si tienes alguna pregunta, no dudes en contactarnos.</small>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MaxiSolutions. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
