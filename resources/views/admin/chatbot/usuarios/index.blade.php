@extends('layouts.admin')

@section('title', 'Usuarios Chatbot - Panel Administrativo')
@section('page-title', 'Usuarios del Chatbot')

@section('content')
<!-- Filtros -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.chatbot.usuarios.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control" placeholder="Nombre o email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Plan</label>
                <select name="plan" class="form-select">
                    <option value="">Todos</option>
                    <option value="gratuito" {{ request('plan') === 'gratuito' ? 'selected' : '' }}>Gratuito</option>
                    <option value="basico" {{ request('plan') === 'basico' ? 'selected' : '' }}>Básico</option>
                    <option value="premium" {{ request('plan') === 'premium' ? 'selected' : '' }}>Premium</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="activo" class="form-select">
                    <option value="">Todos</option>
                    <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Buscar</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Usuarios -->
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-users text-primary"></i> Listado de Usuarios</h5>
        <span class="badge bg-secondary">{{ $usuarios->total() }} usuarios</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Estado</th>
                        <th>Conversaciones</th>
                        <th>Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                    <tr>
                        <td><strong>#{{ $usuario->id }}</strong></td>
                        <td>{{ $usuario->name }}</td>
                        <td><small class="text-muted">{{ $usuario->email }}</small></td>
                        <td>
                            <span class="badge bg-{{ $usuario->plan === 'premium' ? 'success' : ($usuario->plan === 'basico' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($usuario->plan) }}
                            </span>
                        </td>
                        <td>
                            @if($usuario->activo)
                                <span class="badge bg-success"><i class="fas fa-check"></i> Activo</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> Inactivo</span>
                            @endif
                        </td>
                        <td><span class="badge bg-info">{{ $usuario->conversaciones_count }}</span></td>
                        <td><small class="text-muted">{{ $usuario->created_at->format('d/m/Y') }}</small></td>
                        <td>
                            <a href="{{ route('admin.chatbot.usuarios.show', $usuario) }}" class="btn btn-sm btn-outline-primary" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-50"></i>
                            No se encontraron usuarios
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($usuarios->hasPages())
    <div class="card-footer bg-white">
        {{ $usuarios->links() }}
    </div>
    @endif
</div>

<div class="mt-3">
    <a href="{{ route('admin.chatbot.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Volver al Dashboard
    </a>
</div>
@endsection
