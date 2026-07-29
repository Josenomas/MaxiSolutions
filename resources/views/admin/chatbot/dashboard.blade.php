@extends('layouts.admin')

@section('title', 'Dashboard Chatbot - Panel Administrativo')
@section('page-title', 'Dashboard Chatbot')

@section('content')
<!-- Estadísticas Principales -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stats-card bg-gradient-primary text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">{{ $stats['total_usuarios'] }}</h3>
                <small class="opacity-90">Total Usuarios</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stats-card bg-gradient-success text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-user-check fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">{{ $stats['usuarios_activos'] }}</h3>
                <small class="opacity-90">Usuarios Activos</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stats-card bg-gradient-info text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-comments fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">{{ $stats['total_conversaciones'] }}</h3>
                <small class="opacity-90">Conversaciones</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stats-card bg-gradient-warning text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-message fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">{{ $stats['mensajes_hoy'] }}</h3>
                <small class="opacity-90">Mensajes Hoy</small>
            </div>
        </div>
    </div>
</div>

<!-- Distribución de Planes -->
<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="card stats-card bg-light h-100">
            <div class="card-body text-center">
                <i class="fas fa-gift text-secondary fa-2x mb-2"></i>
                <h3 class="mb-0 fw-bold">{{ $stats['usuarios_gratuitos'] }}</h3>
                <small>Plan Gratuito</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card stats-card bg-light h-100">
            <div class="card-body text-center">
                <i class="fas fa-star text-warning fa-2x mb-2"></i>
                <h3 class="mb-0 fw-bold">{{ $stats['usuarios_basicos'] }}</h3>
                <small>Plan Básico</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card stats-card bg-light h-100">
            <div class="card-body text-center">
                <i class="fas fa-crown text-success fa-2x mb-2"></i>
                <h3 class="mb-0 fw-bold">{{ $stats['usuarios_premium'] }}</h3>
                <small>Plan Premium</small>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos y Tablas -->
<div class="row g-4 mb-4">
    <!-- Uso por Día -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-line text-primary"></i> Mensajes por Día (Últimos 7 días)</h5>
            </div>
            <div class="card-body">
                <canvas id="usoPorDiaChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Distribución de Planes (Gráfico) -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-pie text-success"></i> Distribución de Planes</h5>
            </div>
            <div class="card-body">
                <canvas id="distribucionPlanesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Usuarios Recientes y Top Usuarios -->
<div class="row g-4 mb-4">
    <!-- Usuarios Recientes -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user-plus text-info"></i> Usuarios Recientes</h5>
                <a href="{{ route('admin.chatbot.usuarios.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Plan</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuarios_recientes as $usuario)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.chatbot.usuarios.show', $usuario) }}">
                                        {{ $usuario->name }}
                                    </a>
                                </td>
                                <td><small class="text-muted">{{ $usuario->email }}</small></td>
                                <td>
                                    <span class="badge bg-{{ $usuario->plan === 'premium' ? 'success' : ($usuario->plan === 'basico' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($usuario->plan) }}
                                    </span>
                                </td>
                                <td><small class="text-muted">{{ $usuario->created_at->diffForHumans() }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No hay usuarios recientes</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversaciones Recientes -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-comments text-warning"></i> Conversaciones Recientes</h5>
                <a href="{{ route('admin.chatbot.conversaciones.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Usuario</th>
                                <th>Título</th>
                                <th>Mensajes</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($conversaciones_recientes as $conversacion)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.chatbot.usuarios.show', $conversacion->user) }}">
                                        {{ $conversacion->user->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.chatbot.conversaciones.show', $conversacion) }}">
                                        {{ Str::limit($conversacion->titulo, 30) }}
                                    </a>
                                </td>
                                <td><span class="badge bg-info">{{ $conversacion->mensajes_count }}</span></td>
                                <td><small class="text-muted">{{ $conversacion->updated_at->diffForHumans() }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No hay conversaciones recientes</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones Rápidas -->
<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-bolt text-warning"></i> Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('admin.chatbot.usuarios.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-users"></i> Gestionar Usuarios
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.chatbot.conversaciones.index') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-comments"></i> Ver Conversaciones
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.chatbot.configuracion') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-cog"></i> Configuración
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left"></i> Volver al Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Gráfico: Uso por Día
const usoPorDiaCtx = document.getElementById('usoPorDiaChart');
new Chart(usoPorDiaCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($usoPorDia->pluck('dia')) !!},
        datasets: [{
            label: 'Mensajes Enviados',
            data: {!! json_encode($usoPorDia->pluck('total_mensajes')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});

// Gráfico: Distribución de Planes
const distribucionPlanesCtx = document.getElementById('distribucionPlanesChart');
new Chart(distribucionPlanesCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($distribucionPlanes->pluck('plan')->map(fn($p) => ucfirst($p))) !!},
        datasets: [{
            data: {!! json_encode($distribucionPlanes->pluck('total')) !!},
            backgroundColor: [
                'rgba(108, 117, 125, 0.7)',
                'rgba(255, 193, 7, 0.7)',
                'rgba(25, 135, 84, 0.7)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});
</script>
@endpush
