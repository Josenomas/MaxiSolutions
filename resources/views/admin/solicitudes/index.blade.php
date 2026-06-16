@extends('layouts.admin')

@section('title', 'Gestión de Solicitudes')
@section('page-title', 'Solicitudes de Clientes')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitudes as $solicitud)
                        <tr>
                            <td>{{ $solicitud->id }}</td>
                            <td>{{ $solicitud->nombre_cliente }}</td>
                            <td>{{ $solicitud->email_cliente }}</td>
                            <td>{{ $solicitud->telefono_cliente ?? 'N/A' }}</td>
                            <td>{{ $solicitud->servicio->nombre ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $badgeColors = [
                                        'pendiente' => 'warning',
                                        'en_revision' => 'info',
                                        'cotizado' => 'primary',
                                        'aceptado' => 'success',
                                        'rechazado' => 'danger',
                                        'completado' => 'success'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $badgeColors[$solicitud->estado] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
                                </span>
                            </td>
                            <td>{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                            <td class="table-actions">
                                <a href="{{ route('admin.solicitudes.show', $solicitud) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.solicitudes.destroy', $solicitud) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta solicitud?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay solicitudes</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $solicitudes->links() }}
        </div>
    </div>
</div>
@endsection
