@extends('layouts.admin')

@section('title', 'Gestión de Servicios')
@section('page-title', 'Servicios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Lista de Servicios</h4>
    <a href="{{ route('admin.servicios.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Servicio
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio Base</th>
                        <th>Destacado</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios as $servicio)
                        <tr>
                            <td>{{ $servicio->id }}</td>
                            <td>{{ $servicio->nombre }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $servicio->categoria)) }}</span></td>
                            <td>${{ number_format($servicio->precio_base, 2) }}</td>
                            <td>
                                @if($servicio->destacado)
                                    <span class="badge bg-warning">Sí</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $servicio->estado == 'activo' ? 'success' : 'danger' }}">
                                    {{ ucfirst($servicio->estado) }}
                                </span>
                            </td>
                            <td class="table-actions">
                                <a href="{{ route('admin.servicios.edit', $servicio) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.servicios.destroy', $servicio) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este servicio?')">
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
                            <td colspan="7" class="text-center text-muted py-4">No hay servicios registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $servicios->links() }}
        </div>
    </div>
</div>
@endsection
