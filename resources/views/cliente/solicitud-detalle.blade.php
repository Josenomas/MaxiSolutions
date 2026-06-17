@extends('layouts.client')

@section('title', 'Detalle de Solicitud')
@section('page-title', 'Solicitud #' . $solicitud->id)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Cliente</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Nombre:</strong><br>
                        {{ $solicitud->nombre_cliente }}
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong><br>
                        <a href="mailto:{{ $solicitud->email_cliente }}">{{ $solicitud->email_cliente }}</a>
                        <br>
                        @if($clienteRegistrado)
                            <span class="badge bg-success mt-2">
                                <i class="fas fa-user-check"></i> Usuario Registrado
                            </span>
                            <br><small class="text-muted">Cuenta creada: {{ $clienteRegistrado->created_at->format('d/m/Y') }}</small>
                        @else
                            <span class="badge bg-warning text-dark mt-2">
                                <i class="fas fa-user-plus"></i> Sin cuenta en el sistema
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Teléfono:</strong><br>
                        {{ $solicitud->telefono_cliente ?? 'No proporcionado' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Empresa:</strong><br>
                        {{ $solicitud->empresa ?? 'No proporcionado' }}
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Servicio de Interés:</strong><br>
                    {{ $solicitud->servicio->nombre ?? 'No especificado' }}
                </div>
                <div class="mb-3">
                    <strong>Presupuesto Estimado:</strong><br>
                    {{ $solicitud->presupuesto_estimado ?? 'No especificado' }}
                </div>
                <div>
                    <strong>Descripción del Proyecto:</strong><br>
                    <p class="mt-2 p-3 bg-light rounded">{{ $solicitud->descripcion_proyecto }}</p>
                </div>
            </div>
        </div>
        <!-- Historial de Movimientos -->
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history text-info"></i> Historial de Movimientos</h5>
                <span class="badge bg-secondary">{{ $solicitud->historial->count() }} registros</span>
            </div>
            <div class="card-body">
                @if($solicitud->historial->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%"><i class="fas fa-circle"></i></th>
                                    <th>Descripción</th>
                                    <th>Usuario</th>
                                    <th>Fecha</th>
                                    <th>Cambio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($solicitud->historial as $registro)
                                    <tr>
                                        <td>
                                            <i class="fas {{ $registro->accion == 'solicitud_creada' ? 'fa-plus-circle text-success' : ($registro->accion == 'cambio_estado' ? 'fa-exchange-alt text-primary' : 'fa-edit text-warning') }}"></i>
                                        </td>
                                        <td>
                                            <strong>{{ $registro->descripcion }}</strong>
                                            @if($registro->campo)
                                                <br><small class="text-muted">Campo: {{ ucfirst(str_replace('_', ' ', $registro->campo)) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($registro->user)
                                                <i class="fas fa-user-circle"></i> {{ $registro->user->name }}
                                            @else
                                                <span class="text-muted">Sistema</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $registro->created_at->format('d/m/Y H:i') }}</small>
                                            <br><small class="text-muted">{{ $registro->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @if($registro->valor_anterior && $registro->valor_nuevo)
                                                <span class="badge bg-light text-dark border">{{ $registro->valor_anterior }}</span>
                                                <i class="fas fa-arrow-right text-muted mx-1"></i>
                                                <span class="badge bg-primary">{{ $registro->valor_nuevo }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block opacity-50"></i>
                        <p class="text-muted mb-0">No hay movimientos registrados en el historial</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-tasks"></i> Gestión</h5>
            </div>
            <div class="card-body">
                
                
                <hr>

                <div class="text-muted small">
                    <strong>Fecha de solicitud:</strong><br>
                    {{ $solicitud->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <!-- Payment Card -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-credit-card"></i> Pagos</h5>
            </div>
            <div class="card-body">
                @if($solicitud->pagos && $solicitud->pagos->count() > 0)
                    @foreach($solicitud->pagos as $pago)
                        <div class="mb-3 p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>Monto:</strong> ${{ number_format($pago->monto, 0, ',', '.') }} CLP<br>
                                    <strong>Método:</strong> {{ strtoupper($pago->metodo_pago) }}<br>
                                    <strong>Estado:</strong>
                                    @php
                                        $badgeColors = [
                                            'pendiente' => 'warning',
                                            'aprobado' => 'success',
                                            'rechazado' => 'danger',
                                            'anulado' => 'secondary'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $badgeColors[$pago->estado] ?? 'secondary' }}">
                                        {{ ucfirst($pago->estado) }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $pago->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                            @if($pago->referencia_pago)
                                <small class="text-muted d-block mt-2">Ref: {{ $pago->referencia_pago }}</small>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-muted mb-3">No hay pagos registrados</p>
                @endif

                <a href="{{ route('pago.checkout', $solicitud) }}" class="btn btn-success w-100">
                    <i class="fas fa-credit-card"></i> Pagar Ahora
                </a>
                <small class="text-muted d-block mt-2 text-center">Paga con Webpay o Flow</small>
            </div>
        </div>

        <div class="d-grid">
            <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>
    </div>
</div>
@endsection
