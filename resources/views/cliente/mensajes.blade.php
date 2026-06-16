@extends('layouts.client')

@section('title', 'Mensajes')
@section('page-title', 'Mensajes con Soporte')

@section('content')
<div class="card" style="height: 70vh; display: flex; flex-direction: column;">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-comments text-primary"></i> Chat con Administración</h5>
    </div>
    <div class="card-body" style="flex: 1; overflow-y: auto; background: #f8f9fa;" id="chatContainer">
        @forelse($mensajes as $mensaje)
            <div class="mb-3 d-flex {{ $mensaje->usuario_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                <div style="max-width: 70%;">
                    <div class="p-3 rounded {{ $mensaje->usuario_id == auth()->id() ? 'bg-primary text-white' : 'bg-white' }}" style="box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <div style="font-size: 0.85rem; opacity: 0.8; margin-bottom: 5px;">
                            <strong>{{ $mensaje->usuario->name }}</strong>
                            <span class="ms-2">{{ $mensaje->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>{{ $mensaje->mensaje }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                <p>No hay mensajes aún. Inicia la conversación</p>
            </div>
        @endforelse
    </div>
    <div class="card-footer bg-white">
        <form action="{{ route('cliente.mensajes.store') }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" 
                       name="mensaje" 
                       class="form-control" 
                       placeholder="Escribe tu mensaje aquí..."
                       required
                       maxlength="1000">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Enviar
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Scroll automático al último mensaje
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.getElementById('chatContainer');
    chatContainer.scrollTop = chatContainer.scrollHeight;
});
</script>
@endpush
@endsection
