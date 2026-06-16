@extends('layouts.admin')

@section('title', 'Logs de Seguridad')
@section('page-title', 'Logs de Seguridad')

@section('content')
<!-- Estadísticas de Seguridad -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stats-card bg-gradient-info text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-shield-alt fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">{{ number_format($stats['total'], 0, ',', '.') }}</h3>
                <small class="opacity-90">Total Eventos</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stats-card bg-gradient-danger text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">{{ $stats['critical'] }}</h3>
                <small class="opacity-90">Críticos</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stats-card bg-gradient-warning text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-circle fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">{{ $stats['warning'] }}</h3>
                <small class="opacity-90">Advertencias</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stats-card bg-gradient-primary text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">{{ $stats['recent_24h'] }}</h3>
                <small class="opacity-90">Últimas 24h</small>
            </div>
        </div>
    </div>
</div>

<!-- Alerta de Intentos de Login Fallidos -->
@if($stats['login_failures_24h'] > 10)
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>¡Atención!</strong> Se han detectado <strong>{{ $stats['login_failures_24h'] }}</strong> intentos de inicio de sesión fallidos en las últimas 24 horas.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Filtros -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-filter text-primary"></i> Filtros</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.security-logs.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Severidad</label>
                <select name="severity" class="form-select">
                    <option value="">Todas</option>
                    <option value="info" {{ request('severity') == 'info' ? 'selected' : '' }}>Info</option>
                    <option value="warning" {{ request('severity') == 'warning' ? 'selected' : '' }}>Advertencia</option>
                    <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Crítico</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Tipo de Evento</label>
                <select name="event_type" class="form-select">
                    <option value="">Todos</option>
                    <option value="login_success" {{ request('event_type') == 'login_success' ? 'selected' : '' }}>Login Exitoso</option>
                    <option value="login_failed" {{ request('event_type') == 'login_failed' ? 'selected' : '' }}>Login Fallido</option>
                    <option value="unauthorized_access" {{ request('event_type') == 'unauthorized_access' ? 'selected' : '' }}>Acceso No Autorizado</option>
                    <option value="payment_attempt" {{ request('event_type') == 'payment_attempt' ? 'selected' : '' }}>Intento de Pago</option>
                    <option value="data_change" {{ request('event_type') == 'data_change' ? 'selected' : '' }}>Cambio de Datos</option>
                    <option value="suspicious_activity" {{ request('event_type') == 'suspicious_activity' ? 'selected' : '' }}>Actividad Sospechosa</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Usuario ID</label>
                <input type="number" name="user_id" class="form-control" value="{{ request('user_id') }}" placeholder="ID">
            </div>

            <div class="col-md-2">
                <label class="form-label">IP</label>
                <input type="text" name="ip_address" class="form-control" value="{{ request('ip_address') }}" placeholder="IP">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Logs -->
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list"></i> Registro de Eventos</h5>
        <span class="badge bg-secondary">{{ $logs->total() }} eventos</span>
    </div>
    <div class="card-body p-0">
        @if($logs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="80">#ID</th>
                            <th width="120">Severidad</th>
                            <th width="180">Tipo de Evento</th>
                            <th>Usuario</th>
                            <th>IP</th>
                            <th>Descripción</th>
                            <th width="150">Fecha</th>
                            <th width="80" class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr class="{{ $log->severity == 'critical' ? 'table-danger' : ($log->severity == 'warning' ? 'table-warning' : '') }}">
                                <td><strong>#{{ $log->id }}</strong></td>
                                <td>
                                    @if($log->severity == 'critical')
                                        <span class="badge bg-danger"><i class="fas fa-exclamation-triangle"></i> Crítico</span>
                                    @elseif($log->severity == 'warning')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-circle"></i> Advertencia</span>
                                    @else
                                        <span class="badge bg-info"><i class="fas fa-info-circle"></i> Info</span>
                                    @endif
                                </td>
                                <td>
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
                                    <small>{{ str_replace('_', ' ', ucfirst($log->event_type)) }}</small>
                                </td>
                                <td>
                                    @if($log->user)
                                        <a href="#" class="text-decoration-none">
                                            {{ $log->user->name }}
                                            <small class="text-muted d-block">{{ $log->user->email }}</small>
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td><code class="small">{{ $log->ip_address ?? '—' }}</code></td>
                                <td>
                                    <small>{{ Str::limit($log->description, 60) }}</small>
                                </td>
                                <td>
                                    <small>
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                        <span class="text-muted d-block">{{ $log->created_at->diffForHumans() }}</span>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.security-logs.show', $log) }}" class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="card-footer bg-white">
                {{ $logs->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-shield-alt fa-4x mb-3 d-block opacity-50"></i>
                <p class="mb-0">No hay eventos de seguridad registrados</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.bg-gradient-danger { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
.bg-gradient-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.bg-gradient-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stats-card { border: none; transition: transform 0.2s ease; }
.stats-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
.table-responsive { max-height: 600px; }
</style>
@endpush
