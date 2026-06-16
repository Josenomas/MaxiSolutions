<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" style="background: #f5f6fb;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card stats-card bg-gradient-primary text-white h-100" style="border: none;">
                        <div class="card-body text-center">
                            <i class="fas fa-envelope fa-2x mb-2 opacity-75"></i>
                            <h3 class="mb-0 fw-bold">{{ $stats['total_solicitudes'] }}</h3>
                            <small class="opacity-90">Mis Solicitudes</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="card stats-card bg-gradient-warning text-white h-100" style="border: none;">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2 opacity-75"></i>
                            <h3 class="mb-0 fw-bold">{{ $stats['solicitudes_pendientes'] }}</h3>
                            <small class="opacity-90">Pendientes</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="card stats-card bg-gradient-success text-white h-100" style="border: none;">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x mb-2 opacity-75"></i>
                            <h3 class="mb-0 fw-bold">{{ $stats['solicitudes_completadas'] }}</h3>
                            <small class="opacity-90">Completadas</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="card stats-card bg-gradient-danger text-white h-100" style="border: none;">
                        <div class="card-body text-center">
                            <i class="fas fa-cogs fa-2x mb-2 opacity-75"></i>
                            <h3 class="mb-0 fw-bold">{{ $stats['solicitudes_en_proceso'] }}</h3>
                            <small class="opacity-90">En Proceso</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="card stats-card bg-gradient-info text-white h-100" style="border: none;">
                        <div class="card-body text-center">
                            <i class="fas fa-dollar-sign fa-2x mb-2 opacity-75"></i>
                            <h3 class="mb-0 fw-bold">${{ number_format($stats['total_pagado'], 0, ',', '.') }}</h3>
                            <small class="opacity-90">Total Pagado</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mis Solicitudes -->
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list text-primary"></i> Mis Solicitudes</h5>
                    <a href="{{ route('solicitud.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Nueva Solicitud
                    </a>
                </div>
                <div class="card-body">
                    @if($solicitudes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Servicio</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitudes as $solicitud)
                                        <tr>
                                            <td><strong>#{{ $solicitud->id }}</strong></td>
                                            <td>{{ $solicitud->servicio->nombre ?? 'Personalizado' }}</td>
                                            <td>{{ Str::limit($solicitud->descripcion_proyecto, 50) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $solicitud->estado == 'pendiente' ? 'warning' : ($solicitud->estado == 'completado' ? 'success' : 'info') }}">
                                                    {{ ucfirst($solicitud->estado) }}
                                                </span>
                                            </td>
                                            <td>{{ $solicitud->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('solicitud.detalle', $solicitud) }}" class="btn btn-sm btn-primary me-1">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                                @if($solicitud->pagos->where('estado', 'completado')->count() == 0)
                                                    <a href="{{ route('pago.checkout', $solicitud) }}" class="btn btn-sm btn-success">
                                                        <i class="fas fa-credit-card"></i> Pagar
                                                    </a>
                                                @else
                                                    <span class="badge bg-success"><i class="fas fa-check"></i> Pagado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-4x mb-3 d-block opacity-50"></i>
                            <p class="mb-3">No tienes solicitudes aún</p>
                            <a href="{{ route('solicitud.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear mi primera solicitud
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
.bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.bg-gradient-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.bg-gradient-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stats-card { transition: transform 0.2s ease; }
.stats-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
</style>