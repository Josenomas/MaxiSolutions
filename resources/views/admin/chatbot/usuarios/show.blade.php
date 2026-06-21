@extends('layouts.admin')

@section('title', 'Detalle Usuario - ' . $usuario->name)
@section('page-title', 'Detalle de Usuario')

@section('content')
<!-- Información del Usuario -->
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user"></i> Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-circle bg-primary text-white mx-auto mb-3" style="width: 80px; height: 80px; line-height: 80px; border-radius: 50%; font-size: 2rem;">
                        {{ strtoupper(substr($usuario->name, 0, 1)) }}
                    </div>
                    <h4>{{ $usuario->name }}</h4>
                    <p class="text-muted mb-2">{{ $usuario->email }}</p>
                    @if($usuario->telefono)
                    <p class="text-muted mb-0"><i class="fas fa-phone"></i> {{ $usuario->telefono }}</p>
                    @endif
                </div>
                <hr>
                <div class="mb-2">
                    <strong>Plan:</strong>
                    <span class="badge bg-{{ $usuario->plan === 'premium' ? 'success' : ($usuario->plan === 'basico' ? 'warning' : 'secondary') }} float-end">
                        {{ ucfirst($usuario->plan) }}
                    </span>
                </div>
                <div class="mb-2">
                    <strong>Estado:</strong>
                    @if($usuario->activo)
                        <span class="badge bg-success float-end">Activo</span>
                    @else
                        <span class="badge bg-danger float-end">Inactivo</span>
                    @endif
                </div>
                <div class="mb-2">
                    <strong>Registro:</strong>
                    <span class="float-end text-muted">{{ $usuario->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <strong>Última actividad:</strong>
                    <span class="float-end text-muted">{{ $usuario->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Formulario de Edición -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Usuario</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.chatbot.usuarios.update', $usuario) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Plan</label>
                        <select name="plan" class="form-select" required>
                            <option value="gratuito" {{ $usuario->plan === 'gratuito' ? 'selected' : '' }}>Gratuito</option>
                            <option value="basico" {{ $usuario->plan === 'basico' ? 'selected' : '' }}>Básico</option>
                            <option value="premium" {{ $usuario->plan === 'premium' ? 'selected' : '' }}>Premium</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select name="activo" class="form-select" required>
                            <option value="1" {{ $usuario->activo ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ !$usuario->activo ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </form>

                @if(auth()->user()->isSuperAdmin())
                <hr>
                <form method="POST" action="{{ route('admin.chatbot.usuarios.destroy', $usuario) }}" onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Eliminar Usuario
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Estadísticas -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <h3 class="mb-0">{{ $stats['total_conversaciones'] }}</h3>
                        <small>Conversaciones</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-success text-white text-center">
                    <div class="card-body">
                        <h3 class="mb-0">{{ $stats['total_mensajes'] }}</h3>
                        <small>Mensajes</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-warning text-dark text-center">
                    <div class="card-body">
                        <h3 class="mb-0">{{ $stats['mensajes_hoy'] }}</h3>
                        <small>Mensajes Hoy</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-info text-white text-center">
                    <div class="card-body">
                        <h3 class="mb-0">{{ number_format($stats['tokens_usados']) }}</h3>
                        <small>Tokens</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conversaciones del Usuario -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-comments text-info"></i> Conversaciones</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Mensajes</th>
                                <th>Estado</th>
                                <th>Última Actividad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuario->conversaciones as $conversacion)
                            <tr>
                                <td><strong>#{{ $conversacion->id }}</strong></td>
                                <td>{{ Str::limit($conversacion->titulo, 40) }}</td>
                                <td><span class="badge bg-info">{{ $conversacion->mensajes->count() }}</span></td>
                                <td>
                                    @if($conversacion->activa)
                                        <span class="badge bg-success">Activa</span>
                                    @else
                                        <span class="badge bg-secondary">Inactiva</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $conversacion->updated_at->diffForHumans() }}</small></td>
                                <td>
                                    <a href="{{ route('admin.chatbot.conversaciones.show', $conversacion) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Este usuario no tiene conversaciones</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Historial de Uso -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-line text-success"></i> Historial de Uso</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Mensajes Enviados</th>
                                <th>Tokens Usados</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuario->uso->take(10) as $uso)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($uso->fecha)->format('d/m/Y') }}</td>
                                <td><span class="badge bg-primary">{{ $uso->mensajes_enviados }}</span></td>
                                <td><span class="badge bg-info">{{ number_format($uso->tokens_usados) }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">Sin historial de uso</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.chatbot.usuarios.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Volver al Listado
    </a>
</div>
@endsection
