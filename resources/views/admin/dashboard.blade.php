@extends('layouts.admin')

@section('title', 'Dashboard - Panel Administrativo')
@section('page-title', 'Dashboard')

@section('content')
<!-- Estad�sticas Principales -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stats-card bg-gradient-primary text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-briefcase fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">{{$stats['total_servicios']}}</h3>
                <small class="opacity-90">Servicios</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stats-card bg-gradient-success text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-envelope fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">{{ $stats['total_solicitudes'] }}</h3>
                <small class="opacity-90">Solicitudes</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stats-card bg-gradient-warning text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">{{ $stats['solicitudes_pendientes'] }}</h3>
                <small class="opacity-90">Pendientes</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stats-card bg-gradient-info text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">{{ $stats['total_usuarios'] }}</h3>
                <small class="opacity-90">Usuarios</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stats-card bg-gradient-danger text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-dollar-sign fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">${{ number_format($stats['total_pagos'], 0, ',', '.') }}</h3>
                <small class="opacity-90">Total Ingresos</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stats-card bg-gradient-dark text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-calendar-alt fa-2x mb-2 opacity-75"></i>
                <h3 class="mb-0 fw-bold">${{ number_format($stats['pagos_mes'], 0, ',', '.') }}</h3>
                <small class="opacity-90">Este Mes</small>
            </div>
        </div>
    </div>
</div>

<!-- Gestión de Subdominios -->
@if($chatbotStats || $paesStats)
<div class="row g-4 mb-4">
    <div class="col-12">
        <h5 class="mb-3"><i class="fas fa-network-wired text-primary"></i> Gestión de Subdominios</h5>
    </div>

    @if($chatbotStats)
    <div class="col-md-6">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-robot"></i> Chatbot (HateaChistopher)</h5>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <h4 class="text-primary mb-0">{{ $chatbotStats['total_usuarios'] }}</h4>
                        <small class="text-muted">Usuarios</small>
                    </div>
                    <div class="col-4">
                        <h4 class="text-success mb-0">{{ $chatbotStats['total_conversaciones'] }}</h4>
                        <small class="text-muted">Conversaciones</small>
                    </div>
                    <div class="col-4">
                        <h4 class="text-warning mb-0">{{ $chatbotStats['mensajes_hoy'] }}</h4>
                        <small class="text-muted">Mensajes Hoy</small>
                    </div>
                </div>
                <hr>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.chatbot.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i> Dashboard Chatbot
                    </a>
                    <div class="btn-group">
                        <a href="{{ route('admin.chatbot.usuarios.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-users"></i> Usuarios
                        </a>
                        <a href="{{ route('admin.chatbot.conversaciones.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-comments"></i> Conversaciones
                        </a>
                        <a href="{{ route('admin.chatbot.configuracion') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-cog"></i> Configuración
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($paesStats)
    <div class="col-md-6">
        <div class="card shadow-sm border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> PAES</h5>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-wrench fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Panel de administración en desarrollo</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Gr�ficos -->
<div class="row g-4 mb-4">
    <!-- Solicitudes por Mes -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-line text-primary"></i> Solicitudes por Mes</h5>
            </div>
            <div class="card-body">
                <canvas id="solicitudesChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Solicitudes por Estado -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-pie text-success"></i> Por Estado</h5>
            </div>
            <div class="card-body">
                <canvas id="estadosChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Ingresos por Mes -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar text-info"></i> Ingresos por Mes</h5>
            </div>
            <div class="card-body">
                <canvas id="ingresosChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Servicios -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-star text-warning"></i> Top Servicios</h5>
            </div>
            <div class="card-body">
                <canvas id="serviciosChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Solicitudes Recientes -->
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list"></i> Solicitudes Recientes</h5>
        <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-sm btn-primary">Ver todas</a>
    </div>
    <div class="card-body">
        @if($solicitudes_recientes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Email</th>
                            <th>Servicio</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitudes_recientes as $solicitud)
                            <tr>
                                <td><strong>#{{ $solicitud->id }}</strong></td>
                                <td>{{ $solicitud->nombre_cliente }}</td>
                                <td><small>{{ $solicitud->email_cliente }}</small></td>
                                <td>{{ $solicitud->servicio->nombre ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $solicitud->estado == 'pendiente' ? 'warning' : ($solicitud->estado == 'completado' ? 'success' : 'info') }}">
                                        {{ ucfirst($solicitud->estado) }}
                                    </span>
                                </td>
                                <td>{{ $solicitud->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.solicitudes.show', $solicitud) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-4x mb-3 d-block opacity-50"></i>
                <p class="mb-0">No hay solicitudes a�n</p>
            </div>
        @endif
    </div>
</div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
const solicitudesPorMes = @json($solicitudesPorMes);
const solicitudesPorEstado = @json($solicitudesPorEstado);
const ingresosPorMes = @json($ingresosPorMes);
const serviciosMasSolicitados = @json($serviciosMasSolicitados);

// Solicitudes por Mes
new Chart(document.getElementById('solicitudesChart'), {
    type: 'line',
    data: {
        labels: solicitudesPorMes.map(item => meses[item.mes - 1]),
        datasets: [{
            label: 'Solicitudes',
            data: solicitudesPorMes.map(item => item.total),
            borderColor: '#5b54f0',
            backgroundColor: 'rgba(91, 84, 240, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: { responsive: true, maintainAspectRatio: true }
});

// Solicitudes por Estado
new Chart(document.getElementById('estadosChart'), {
    type: 'doughnut',
    data: {
        labels: solicitudesPorEstado.map(item => item.estado.charAt(0).toUpperCase() + item.estado.slice(1)),
        datasets: [{
            data: solicitudesPorEstado.map(item => item.total),
            backgroundColor: ['#ffc107', '#28a745', '#17a2b8', '#6c757d']
        }]
    }
});

// Ingresos por Mes
new Chart(document.getElementById('ingresosChart'), {
    type: 'bar',
    data: {
        labels: ingresosPorMes.map(item => meses[item.mes - 1]),
        datasets: [{
            label: 'Ingresos (CLP)',
            data: ingresosPorMes.map(item => item.total),
            backgroundColor: 'rgba(23, 162, 184, 0.8)'
        }]
    }
});

// Top Servicios
new Chart(document.getElementById('serviciosChart'), {
    type: 'bar',
    data: {
        labels: serviciosMasSolicitados.map(item => item.nombre.length > 20 ? item.nombre.substring(0, 20) + '...' : item.nombre),
        datasets: [{
            data: serviciosMasSolicitados.map(item => item.solicitudes_count),
            backgroundColor: ['#ffc107', '#28a745', '#007bff', '#dc3545', '#6c757d']
        }]
    },
    options: { indexAxis: 'y' }
});
</script>
@endpush

@push('styles')
<style>
.bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.bg-gradient-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.bg-gradient-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.bg-gradient-danger { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
.bg-gradient-dark { background: linear-gradient(135deg, #5b54f0 0%, #8b5cf6 100%); }
.stats-card { border: none; transition: transform 0.2s ease; }
.stats-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
</style>
@endpush
