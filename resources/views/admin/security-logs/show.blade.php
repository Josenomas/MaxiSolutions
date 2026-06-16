@extends('layouts.admin')

@section('title', 'Detalle de Evento de Seguridad #' . $log->id)
@section('page-title', 'Detalle de Evento de Seguridad')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.security-logs.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver a Logs
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Información Principal -->
        <div class="card shadow-sm mb-4">
            <div class="card-header {{ $log->severity == 'critical' ? 'bg-danger text-white' : ($log->severity == 'warning' ? 'bg-warning' : 'bg-info text-white') }}">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Información del Evento #{{ $log->id }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Tipo de Evento</label>
                        <p class="fw-bold">
                            @php
                                $eventIcons = [
                                    'login_success' => 'sign-in-alt',
                                    'login_failed' => 'times-circle',
                                    'unauthorized_access' => 'ban',
                                    'payment_attempt' => 'credit-card',
                                    'data_change' => 'edit',
                                    'suspicious_activity' => 'user-secret'
                                ];
                                $icon = $eventIcons[$log->event_type] ?? 'circle';
                            @endphp
                            <i class="fas fa-{{ $icon }} me-1"></i>
                            {{ str_replace('_', ' ', ucwords($log->event_type)) }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Severidad</label>
                        <p>
                            @if($log->severity == 'critical')
                                <span class="badge bg-danger fs-6"><i class="fas fa-exclamation-triangle"></i> Crítico</span>
                            @elseif($log->severity == 'warning')
                                <span class="badge bg-warning text-dark fs-6"><i class="fas fa-exclamation-circle"></i> Advertencia</span>
                            @else
                                <span class="badge bg-info fs-6"><i class="fas fa-info-circle"></i> Info</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Fecha y Hora</label>
                        <p class="fw-bold">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                            <small class="text-muted d-block">{{ $log->created_at->diffForHumans() }}</small>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Usuario</label>
                        <p>
                            @if($log->user)
                                <strong>{{ $log->user->name }}</strong>
                                <small class="text-muted d-block">{{ $log->user->email }}</small>
                                <small class="text-muted d-block">ID: {{ $log->user_id }}</small>
                            @else
                                <span class="text-muted">Sin usuario asociado</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small mb-1">Descripción</label>
                    <p class="fw-bold">{{ $log->description }}</p>
                </div>
            </div>
        </div>

        <!-- Detalles de la Solicitud -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-globe me-2"></i>Detalles de la Solicitud HTTP</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Método HTTP</label>
                        <p>
                            @if($log->method)
                                <span class="badge bg-primary">{{ $log->method }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-8">
                        <label class="text-muted small mb-1">URL</label>
                        <p><code class="small">{{ $log->url ?? '—' }}</code></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Dirección IP</label>
                        <p><code>{{ $log->ip_address ?? '—' }}</code></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">User Agent</label>
                        <p class="small text-break">{{ $log->user_agent ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metadata Adicional -->
        @if($log->metadata)
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-database me-2"></i>Datos Adicionales (Metadata)</h5>
            </div>
            <div class="card-body">
                <pre class="bg-light p-3 rounded"><code>{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Acciones Rápidas -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Acciones</h6>
            </div>
            <div class="card-body">
                @if($log->user_id)
                    <a href="#" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-user me-1"></i> Ver Usuario
                    </a>
                @endif

                @if($log->ip_address)
                    <a href="{{ route('admin.security-logs.index', ['ip_address' => $log->ip_address]) }}" class="btn btn-outline-info w-100 mb-2">
                        <i class="fas fa-search me-1"></i> Buscar misma IP
                    </a>
                @endif

                <a href="{{ route('admin.security-logs.index', ['event_type' => $log->event_type]) }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-filter me-1"></i> Ver eventos similares
                </a>
            </div>
        </div>

        <!-- Recomendaciones de Seguridad -->
        @if($log->severity == 'critical' || $log->event_type == 'suspicious_activity')
        <div class="card shadow-sm border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Recomendaciones</h6>
            </div>
            <div class="card-body">
                <ul class="mb-0 small">
                    @if($log->event_type == 'login_failed')
                        <li>Verificar si la IP está intentando un ataque de fuerza bruta</li>
                        <li>Considerar bloquear la IP si hay múltiples intentos</li>
                        <li>Verificar si el usuario necesita restablecer su contraseña</li>
                    @elseif($log->event_type == 'unauthorized_access')
                        <li>Revisar los permisos del usuario</li>
                        <li>Verificar si es un intento de escalación de privilegios</li>
                        <li>Considerar auditoría de seguridad del usuario</li>
                    @elseif($log->event_type == 'suspicious_activity')
                        <li>Investigar el comportamiento del usuario</li>
                        <li>Verificar actividad reciente de la cuenta</li>
                        <li>Considerar suspensión temporal si es necesario</li>
                    @else
                        <li>Revisar el contexto del evento</li>
                        <li>Verificar si es parte de un patrón</li>
                        <li>Documentar para futura referencia</li>
                    @endif
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
pre code {
    display: block;
    max-height: 400px;
    overflow-y: auto;
    font-size: 0.85rem;
}
</style>
@endpush
