@extends('layouts.admin')

@section('title', 'Conversaciones Chatbot - Panel Administrativo')
@section('page-title', 'Conversaciones del Chatbot')

@section('content')
<!-- Filtros -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.chatbot.conversaciones.index') }}" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control" placeholder="Usuario, email o título..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="activa" class="form-select">
                    <option value="">Todas</option>
                    <option value="1" {{ request('activa') === '1' ? 'selected' : '' }}>Activas</option>
                    <option value="0" {{ request('activa') === '0' ? 'selected' : '' }}>Inactivas</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Buscar</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Conversaciones -->
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-comments text-info"></i> Listado de Conversaciones</h5>
        <span class="badge bg-secondary">{{ $conversaciones->total() }} conversaciones</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Título</th>
                        <th>Mensajes</th>
                        <th>Estado</th>
                        <th>Última Actividad</th>
                        <th>Creada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($conversaciones as $conversacion)
                    <tr>
                        <td><strong>#{{ $conversacion->id }}</strong></td>
                        <td>
                            <a href="{{ route('admin.chatbot.usuarios.show', $conversacion->user) }}">
                                {{ $conversacion->user->name }}
                            </a>
                            <br>
                            <small class="text-muted">{{ $conversacion->user->email }}</small>
                        </td>
                        <td>{{ Str::limit($conversacion->titulo, 40) }}</td>
                        <td><span class="badge bg-info">{{ $conversacion->mensajes_count }}</span></td>
                        <td>
                            @if($conversacion->activa)
                                <span class="badge bg-success"><i class="fas fa-check"></i> Activa</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-times"></i> Inactiva</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $conversacion->updated_at->diffForHumans() }}</small></td>
                        <td><small class="text-muted">{{ $conversacion->created_at->format('d/m/Y') }}</small></td>
                        <td>
                            <a href="{{ route('admin.chatbot.conversaciones.show', $conversacion) }}" class="btn btn-sm btn-outline-primary" title="Ver conversación">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-50"></i>
                            No se encontraron conversaciones
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($conversaciones->hasPages())
    <div class="card-footer bg-white">
        {{ $conversaciones->links() }}
    </div>
    @endif
</div>

<div class="mt-3">
    <a href="{{ route('admin.chatbot.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Volver al Dashboard
    </a>
</div>
@endsection
