@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')
@section('page-title', 'Usuarios del Sistema')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Lista de Usuarios</h5>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Password Temporal</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                <span class="badge bg-{{ $usuario->tipo_usuario === 'admin' ? 'danger' : 'primary' }}">
                                    {{ ucfirst($usuario->tipo_usuario) }}
                                </span>
                            </td>
                            <td>
                                @if($usuario->debe_cambiar_password)
                                    <span class="badge bg-warning">Sí</span>
                                @else
                                    <span class="badge bg-success">No</span>
                                @endif
                            </td>
                            <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.usuarios.reset-password', $usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Resetear contraseña de este usuario?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" title="Resetear Contraseña">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </form>
                                @if($usuario->id !== auth()->id())
                                    <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay usuarios registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $usuarios->links() }}
        </div>
    </div>
</div>
@endsection
