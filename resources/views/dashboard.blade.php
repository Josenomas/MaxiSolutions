@extends('layouts.client')

@section('title', 'Mi Dashboard')
@section('page-title', 'Bienvenido, ' . auth()->user()->name)

@section('content')
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-4 col-lg">
        <div class="card stats-card h-100" style="border: none; border-left: 4px solid #667eea;">
            <div class="card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 5px;">Total Solicitudes</div>
                        <h3 style="font-size: 2rem; font-weight: 700; margin: 0;">{{ $stats['total_solicitudes'] }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(102, 126, 234, 0.1); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-file-alt" style="font-size: 1.5rem; color: #667eea;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg">
        <div class="card stats-card h-100" style="border: none; border-left: 4px solid #ffc107;">
            <div class="card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 5px;">Pendientes</div>
                        <h3 style="font-size: 2rem; font-weight: 700; margin: 0;">{{ $stats['solicitudes_pendientes'] }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(255, 193, 7, 0.1); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock" style="font-size: 1.5rem; color: #ffc107;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg">
        <div class="card stats-card h-100" style="border: none; border-left: 4px solid #28a745;">
            <div class="card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 5px;">Completadas</div>
                        <h3 style="font-size: 2rem; font-weight: 700; margin: 0;">{{ $stats['solicitudes_completadas'] }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(40, 167, 69, 0.1); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check-circle" style="font-size: 1.5rem; color: #28a745;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg">
        <div class="card stats-card h-100" style="border: none; border-left: 4px solid #17a2b8;">
            <div class="card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 5px;">En Proceso</div>
                        <h3 style="font-size: 2rem; font-weight: 700; margin: 0;">{{ $stats['solicitudes_en_proceso'] }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(23, 162, 184, 0.1); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-cogs" style="font-size: 1.5rem; color: #17a2b8;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg">
        <div class="card stats-card h-100" style="border: none; border-left: 4px solid #dc3545;">
            <div class="card-body">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 5px;">Total Pagado</div>
                        <h3 style="font-size: 1.5rem; font-weight: 700; margin: 0;">${{ number_format($stats['total_pagado'], 0, ',', '.') }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(220, 53, 69, 0.1); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-dollar-sign" style="font-size: 1.5rem; color: #dc3545;"></i>
                    </div>
                </div>
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
                                    <span class="badge bg-{{ $solicitud->estado_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
                                    </span>
                                </td>
                                <td>{{ $solicitud->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('cliente.solicitud.show', $solicitud) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Ver
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
                <p class="mb-3">No tienes solicitudes aún</p>
                <a href="{{ route('solicitud.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear mi primera solicitud
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
