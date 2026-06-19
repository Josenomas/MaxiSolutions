<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Boleta Electrónica - {{ $pago->buy_order ?? $pago->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
            background-color: #4F46E5;
            color: white;
            padding: 10px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .detail-table th {
            background-color: #f3f4f6;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .detail-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #4F46E5;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .stamp {
            margin-top: 30px;
            text-align: center;
            padding: 15px;
            border: 2px solid #22c55e;
            color: #22c55e;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">MaxiSolutions</div>
        <div>Soluciones Tecnológicas que Impulsan tu Negocio</div>
        <div>maxisolutions.cl</div>
        <div class="document-title">BOLETA ELECTRÓNICA</div>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td>Número de Boleta:</td>
                <td>{{ str_pad($pago->id, 8, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td>Fecha de Emisión:</td>
                <td>{{ $pago->fecha_confirmacion ? $pago->fecha_confirmacion->format('d/m/Y H:i') : $pago->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Orden de Compra:</td>
                <td>{{ $pago->buy_order ?? $pago->referencia_pago }}</td>
            </tr>
            @if($pago->referencia_pago)
            <tr>
                <td>Referencia de Pago:</td>
                <td>{{ $pago->referencia_pago }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="info-section">
        <h3 style="border-bottom: 2px solid #4F46E5; padding-bottom: 5px;">Datos del Cliente</h3>
        <table class="info-table">
            <tr>
                <td>Nombre:</td>
                <td>{{ $pago->solicitud->usuario->name ?? $pago->solicitud->nombre_cliente }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{{ $pago->solicitud->usuario->email ?? $pago->solicitud->email_cliente }}</td>
            </tr>
            @if($pago->solicitud->telefono_cliente)
            <tr>
                <td>Teléfono:</td>
                <td>{{ $pago->solicitud->telefono_cliente }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="info-section">
        <h3 style="border-bottom: 2px solid #4F46E5; padding-bottom: 5px;">Detalle del Servicio</h3>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th style="text-align: right; width: 30%;">Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $pago->solicitud->servicio->nombre ?? 'Servicio' }}</strong><br>
                        <small>Solicitud #{{ $pago->solicitud->id }}</small>
                        @if($pago->solicitud->descripcion)
                        <br><small style="color: #666;">{{ Str::limit($pago->solicitud->descripcion, 100) }}</small>
                        @endif
                    </td>
                    <td style="text-align: right;">${{ number_format($pago->monto, 0, ',', '.') }} CLP</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="total-section">
        <div style="margin-bottom: 10px;">
            <strong>Método de Pago:</strong> {{ strtoupper($pago->metodo_pago) }}
        </div>
        @if($pago->response_data && isset($pago->response_data['authorization_code']))
        <div style="margin-bottom: 10px;">
            <strong>Código de Autorización:</strong> {{ $pago->response_data['authorization_code'] }}
        </div>
        @endif
        <div class="total-amount">
            TOTAL PAGADO: ${{ number_format($pago->monto, 0, ',', '.') }} CLP
        </div>
    </div>

    <div class="stamp">
        ✓ PAGO CONFIRMADO
    </div>

    <div class="footer">
        <p>Este documento es una boleta electrónica válida como comprobante de pago.</p>
        <p>MaxiSolutions - Soluciones Tecnológicas | maxisolutions.cl</p>
        <p>Documento generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
