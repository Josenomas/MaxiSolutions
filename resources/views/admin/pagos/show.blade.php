@extends('layouts.admin')

@section('title', 'Detalle de Pago')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <a href="{{ route('admin.pagos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Pagos
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-invoice-dollar"></i> Información del Pago</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">ID de Pago:</th>
                            <td>#{{ str_pad($pago->id, 8, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <th>Solicitud:</th>
                            <td>
                                <a href="{{ route('admin.solicitudes.show', $pago->solicitud) }}">
                                    #{{ $pago->solicitud->id }} - {{ $pago->solicitud->servicio->nombre ?? 'N/A' }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Cliente:</th>
                            <td>{{ $pago->solicitud->usuario->name ?? $pago->solicitud->nombre_cliente }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $pago->solicitud->usuario->email ?? $pago->solicitud->email_cliente }}</td>
                        </tr>
                        <tr>
                            <th>Monto:</th>
                            <td class="fs-4"><strong>${{ number_format($pago->monto, 0, ',', '.') }} CLP</strong></td>
                        </tr>
                        <tr>
                            <th>Método de Pago:</th>
                            <td>
                                @if($pago->metodo_pago === 'webpay')
                                    <span class="badge bg-primary">WEBPAY PLUS</span>
                                @elseif($pago->metodo_pago === 'flow')
                                    <span class="badge bg-success">FLOW</span>
                                @else
                                    <span class="badge bg-secondary">{{ strtoupper($pago->metodo_pago) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                @if($pago->estado === 'aprobado' || $pago->estado === 'completado')
                                    <span class="badge bg-success fs-6">APROBADO</span>
                                @elseif($pago->estado === 'pendiente')
                                    <span class="badge bg-warning fs-6">PENDIENTE</span>
                                @elseif($pago->estado === 'fallido')
                                    <span class="badge bg-danger fs-6">FALLIDO</span>
                                @else
                                    <span class="badge bg-secondary fs-6">{{ strtoupper($pago->estado) }}</span>
                                @endif
                            </td>
                        </tr>
                        @if($pago->buy_order)
                        <tr>
                            <th>Orden de Compra:</th>
                            <td><code>{{ $pago->buy_order }}</code></td>
                        </tr>
                        @endif
                        @if($pago->referencia_pago)
                        <tr>
                            <th>Referencia de Pago:</th>
                            <td><code>{{ $pago->referencia_pago }}</code></td>
                        </tr>
                        @endif
                        @if($pago->token)
                        <tr>
                            <th>Token:</th>
                            <td><small><code>{{ $pago->token }}</code></small></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($pago->response_data && count($pago->response_data) > 0)
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-code"></i> Datos de Respuesta de la Pasarela</h6>
                </div>
                <div class="card-body">
                    <pre class="bg-light p-3 rounded"><code>{{ json_encode($pago->response_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-clock"></i> Fechas</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Creado:</strong><br>
                        {{ $pago->created_at->format('d/m/Y H:i:s') }}
                    </p>
                    @if($pago->fecha_confirmacion)
                    <p class="mb-2">
                        <strong>Confirmado:</strong><br>
                        {{ $pago->fecha_confirmacion->format('d/m/Y H:i:s') }}
                    </p>
                    @endif
                    <p class="mb-0">
                        <strong>Actualizado:</strong><br>
                        {{ $pago->updated_at->format('d/m/Y H:i:s') }}
                    </p>
                </div>
            </div>

            @if($pago->estado === 'aprobado' || $pago->estado === 'completado')
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-download"></i> Acciones</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('pago.boleta.public', ['pago' => $pago->id, 'hash' => md5($pago->id . $pago->created_at)]) }}"
                       class="btn btn-success w-100 mb-2" target="_blank">
                        <i class="fas fa-file-pdf"></i> Ver Boleta Electrónica
                    </a>
                    <a href="{{ route('admin.solicitudes.show', $pago->solicitud) }}" class="btn btn-primary w-100">
                        <i class="fas fa-eye"></i> Ver Solicitud
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
