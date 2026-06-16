@extends('layouts.admin')

@section('title', 'Mensajes de Clientes')
@section('page-title', 'Mensajes de Clientes')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-comments"></i> Conversaciones con Clientes</h5>
    </div>
    <div class="card-body">
        @if(count($clientes) > 0)
            <div class="list-group">
                @foreach($clientes as $cliente)
                    <a href="{{ route('admin.mensajes.show', $cliente['usuario']) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $cliente['usuario']->name }}</h6>
                                    <small class="text-muted">{{ $cliente['usuario']->email }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                @if($cliente['no_leidos'] > 0)
                                    <span class="badge bg-danger">{{ $cliente['no_leidos'] }} nuevo(s)</span>
                                @endif
                                <div class="small text-muted mt-1">
                                    {{ \Carbon\Carbon::parse($cliente['ultimo_mensaje'])->diffForHumans() }}
                                </div>
                                <div class="small text-muted">
                                    <i class="fas fa-comments"></i> {{ $cliente['total_mensajes'] }} mensajes
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <p class="text-muted">No hay mensajes de clientes aún</p>
            </div>
        @endif
    </div>
</div>
@endsection
