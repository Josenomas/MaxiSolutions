@extends('layouts.admin')

@section('title', 'Conversación con ' . $usuario->name)
@section('page-title', 'Conversación con ' . $usuario->name)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.mensajes.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Volver a Mensajes
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user text-white"></i>
                </div>
            </div>
            <div>
                <h5 class="mb-0">{{ $usuario->name }}</h5>
                <small class="text-muted">{{ $usuario->email }}</small>
            </div>
        </div>
    </div>
    <div class="card-body" style="height: 500px; overflow-y: auto;" id="chatContainer">
        @if($mensajes->count() > 0)
            @foreach($mensajes as $mensaje)
                <div class="mb-3 {{ $mensaje->es_admin ? 'text-end' : '' }}">
                    <div class="d-inline-block" style="max-width: 70%;">
                        <div class="p-3 rounded {{ $mensaje->es_admin ? 'bg-primary text-white' : 'bg-light' }}">
                            <div class="mb-1">{{ $mensaje->mensaje }}</div>
                            <small class="{{ $mensaje->es_admin ? 'text-white-50' : 'text-muted' }}">
                                {{ $mensaje->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div class="mt-1">
                            <small class="text-muted">{{ $mensaje->usuario->name }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-comments fa-3x mb-3"></i>
                <p>No hay mensajes en esta conversación</p>
            </div>
        @endif
    </div>
    <div class="card-footer bg-white">
        <form action="{{ route('admin.mensajes.reply', $usuario) }}" method="POST">
            @csrf
            <div class="input-group">
                <textarea name="mensaje" class="form-control" rows="2" placeholder="Escribe tu respuesta..." required></textarea>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Enviar
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto-scroll to bottom
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chatContainer');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    });
</script>
@endpush
@endsection
