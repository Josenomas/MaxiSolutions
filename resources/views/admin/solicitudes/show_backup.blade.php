@extends('layouts.admin')

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
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-tasks"></i> Gestión</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.solicitudes.update', $solicitud) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select" required>
                            <option value="pendiente" {{ $solicitud->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_revision" {{ $solicitud->estado == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                            <option value="cotizado" {{ $solicitud->estado == 'cotizado' ? 'selected' : '' }}>Cotizado</option>
                            <option value="aceptado" {{ $solicitud->estado == 'aceptado' ? 'selected' : '' }}>Aceptado</option>
                            <option value="rechazado" {{ $solicitud->estado == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                            <option value="completado" {{ $solicitud->estado == 'completado' ? 'selected' : '' }}>Completado</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notas del Administrador</label>
                        <textarea name="notas_admin" class="form-control" rows="4" placeholder="Agregar notas internas...">{{ $solicitud->notas_admin }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Actualizar</button>
                </form>
                
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

                <a href="{{ route('pago.checkout', $solicitud) }}" class="btn btn-success w-100" target="_blank">
                    <i class="fas fa-plus"></i> Enviar Link de Pago
                </a>
                <small class="text-muted d-block mt-2 text-center">Comparte este link con el cliente</small>
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
