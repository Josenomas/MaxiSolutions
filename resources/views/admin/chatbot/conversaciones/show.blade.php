@extends('layouts.admin')

@section('title', 'Conversación #' . $conversacion->id)
@section('page-title', 'Detalle de Conversación')

@section('content')
<div class="row g-4">
    <!-- Información de la Conversación -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>ID:</strong>
                    <span class="float-end">#{{ $conversacion->id }}</span>
                </div>
                <div class="mb-3">
                    <strong>Título:</strong>
                    <p class="mb-0 mt-1">{{ $conversacion->titulo }}</p>
                </div>
                <div class="mb-3">
                    <strong>Usuario:</strong>
                    <p class="mb-0 mt-1">
                        <a href="{{ route('admin.chatbot.usuarios.show', $conversacion->user) }}">
                            {{ $conversacion->user->name }}
                        </a>
                        <br>
                        <small class="text-muted">{{ $conversacion->user->email }}</small>
                    </p>
                </div>
                <div class="mb-3">
                    <strong>Estado:</strong>
                    @if($conversacion->activa)
                        <span class="badge bg-success float-end">Activa</span>
                    @else
                        <span class="badge bg-secondary float-end">Inactiva</span>
                    @endif
                </div>
                <div class="mb-3">
                    <strong>Total Mensajes:</strong>
                    <span class="badge bg-info float-end">{{ $conversacion->mensajes->count() }}</span>
                </div>
                <div class="mb-3">
                    <strong>Creada:</strong>
                    <span class="float-end text-muted">{{ $conversacion->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <strong>Última Actividad:</strong>
                    <span class="float-end text-muted">{{ $conversacion->updated_at->diffForHumans() }}</span>
                </div>
            </div>

            @if(auth()->user()->isSuperAdmin())
            <div class="card-footer bg-white">
                <form method="POST" action="{{ route('admin.chatbot.conversaciones.destroy', $conversacion) }}" onsubmit="return confirm('¿Estás seguro de eliminar esta conversación? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Eliminar Conversación
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- Mensajes de la Conversación -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-comments text-primary"></i> Mensajes ({{ $conversacion->mensajes->count() }})</h5>
            </div>
            <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                @forelse($conversacion->mensajes as $mensaje)
                <div class="mb-3 {{ $mensaje->role === 'user' ? 'text-end' : '' }}">
                    <div class="d-inline-block {{ $mensaje->role === 'user' ? 'bg-primary text-white' : 'bg-light' }} p-3 rounded" style="max-width: 70%;">
                        <div class="mb-1">
                            @if($mensaje->role === 'user')
                                <strong><i class="fas fa-user"></i> Usuario</strong>
                            @elseif($mensaje->role === 'assistant')
                                <strong><i class="fas fa-robot"></i> Asistente</strong>
                            @else
                                <strong><i class="fas fa-cog"></i> Sistema</strong>
                            @endif
                            <small class="ms-2 opacity-75">{{ $mensaje->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <div>{{ $mensaje->contenido }}</div>
                        @if($mensaje->metadata)
                        <div class="mt-2 pt-2 border-top border-secondary">
                            <small class="opacity-75">
                                <i class="fas fa-info-circle"></i> Metadata:
                                <pre class="mb-0 mt-1" style="font-size: 0.75rem;">{{ json_encode($mensaje->metadata, JSON_PRETTY_PRINT) }}</pre>
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                    <p>No hay mensajes en esta conversación</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Timeline/Actividad -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-clock text-warning"></i> Timeline de Actividad</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($conversacion->mensajes->reverse() as $mensaje)
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker {{ $mensaje->role === 'user' ? 'bg-primary' : 'bg-success' }}"></div>
                        <div class="timeline-content">
                            <small class="text-muted">{{ $mensaje->created_at->format('d/m/Y H:i:s') }}</small>
                            <p class="mb-0">
                                <strong>{{ $mensaje->role === 'user' ? 'Usuario' : 'Asistente' }}</strong>
                                envió un mensaje
                                ({{ strlen($mensaje->contenido) }} caracteres)
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.chatbot.conversaciones.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Volver al Listado
    </a>
    <a href="{{ route('admin.chatbot.usuarios.show', $conversacion->user) }}" class="btn btn-outline-primary">
        <i class="fas fa-user"></i> Ver Usuario
    </a>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
.timeline-item {
    position: relative;
}
.timeline-marker {
    position: absolute;
    left: -26px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid white;
}
</style>
@endsection
