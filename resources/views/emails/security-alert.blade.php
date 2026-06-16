<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta de Seguridad</title>
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
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .critical-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
        }
        .detail-row {
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row strong {
            display: inline-block;
            width: 150px;
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
            <h1>🚨 Alerta de Seguridad</h1>
            <p>MaxiSolutions Security Monitor</p>
        </div>
        <div class="content">
            <p><strong>Se ha detectado un evento de seguridad crítico en el sistema.</strong></p>
            
            <div class="alert-box {{ $log->severity === 'critical' ? 'critical-box' : '' }}">
                <div class="detail-row">
                    <strong>Tipo de Evento:</strong> {{ $log->event_type }}
                </div>
                <div class="detail-row">
                    <strong>Severidad:</strong> 
                    @if($log->severity === 'critical')
                        <span style="color: #dc3545; font-weight: bold;">CRÍTICO</span>
                    @elseif($log->severity === 'warning')
                        <span style="color: #ffc107; font-weight: bold;">ADVERTENCIA</span>
                    @else
                        <span style="color: #28a745;">INFO</span>
                    @endif
                </div>
                <div class="detail-row">
                    <strong>Descripción:</strong> {{ $log->description }}
                </div>
                <div class="detail-row">
                    <strong>Fecha/Hora:</strong> {{ $log->created_at->format('d/m/Y H:i:s') }}
                </div>
                <div class="detail-row">
                    <strong>IP Address:</strong> {{ $log->ip_address ?? 'N/A' }}
                </div>
                <div class="detail-row">
                    <strong>URL:</strong> {{ $log->url ?? 'N/A' }}
                </div>
                <div class="detail-row">
                    <strong>Usuario:</strong> {{ $log->user ? $log->user->name . ' (' . $log->user->email . ')' : 'No autenticado' }}
                </div>
            </div>

            @if($log->metadata)
                <p><strong>Información Adicional:</strong></p>
                <div class="alert-box">
                    @foreach($log->metadata as $key => $value)
                        <div class="detail-row">
                            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                            {{ is_array($value) ? json_encode($value) : $value }}
                        </div>
                    @endforeach
                </div>
            @endif

            <p style="margin-top: 30px; color: #666;">
                <strong>Acciones Recomendadas:</strong><br>
                1. Revisa inmediatamente el panel de seguridad<br>
                2. Verifica si este evento es legítimo<br>
                3. Toma medidas correctivas si es necesario<br>
                4. Considera bloquear la IP si es un ataque
            </p>

            <p style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/admin/security-logs') }}" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    Ver Panel de Seguridad
                </a>
            </p>
        </div>
        <div class="footer">
            Este es un email automático de seguridad. No responder.<br>
            &copy; {{ date('Y') }} MaxiSolutions. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
