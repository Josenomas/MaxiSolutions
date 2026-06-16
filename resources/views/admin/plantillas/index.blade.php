@extends('layouts.admin')

@section('title', 'Gestión de Plantillas')
@section('page-title', 'Plantillas de Respuesta')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle"></i> Gestiona plantillas predefinidas con variables dinámicas para agilizar la comunicación
                </p>
            </div>
            <a href="{{ route('admin.plantillas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Plantilla
            </a>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.plantillas.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Filtrar por Tipo</label>
                <select name="tipo" class="form-select" onchange="this.form.submit()">
                    <option value="">Todos los tipos</option>
                    <option value="comentario" {{ request('tipo') == 'comentario' ? 'selected' : '' }}>📝 Comentario</option>
                    <option value="email" {{ request('tipo') == 'email' ? 'selected' : '' }}>✉️ Email</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="activa" class="form-select" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    <option value="1" {{ request('activa') === '1' ? 'selected' : '' }}>✅ Activas</option>
                    <option value="0" {{ request('activa') === '0' ? 'selected' : '' }}>❌ Inactivas</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <a href="{{ route('admin.plantillas.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-redo"></i> Limpiar Filtros
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Plantillas -->
<div class="card">
    <div class="card-body">
        @if($plantillas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="25%">Nombre</th>
                            <th width="10%">Tipo</th>
                            <th width="35%">Descripción</th>
                            <th width="10%" class="text-center">Usos</th>
                            <th width="10%" class="text-center">Estado</th>
                            <th width="15%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plantillas as $plantilla)
                            <tr>
                                <td><strong>#{{ $plantilla->id }}</strong></td>
                                <td>
                                    <strong>{{ $plantilla->nombre }}</strong>
                                    @if($plantilla->tipo == 'email' && $plantilla->asunto)
                                        <br><small class="text-muted"><i class="fas fa-envelope"></i> {{ Str::limit($plantilla->asunto, 40) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($plantilla->tipo == 'comentario')
                                        <span class="badge bg-info">📝 Comentario</span>
                                    @else
                                        <span class="badge bg-primary">✉️ Email</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $plantilla->descripcion ?? 'Sin descripción' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $plantilla->veces_usada }}</span>
                                </td>
                                <td class="text-center">
                                    @if($plantilla->activa)
                                        <span class="badge bg-success">✅ Activa</span>
                                    @else
                                        <span class="badge bg-danger">❌ Inactiva</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.plantillas.edit', $plantilla) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#previewModal{{ $plantilla->id }}"
                                                title="Vista Previa">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <form action="{{ route('admin.plantillas.destroy', $plantilla) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta plantilla?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Preview -->
                            <div class="modal fade" id="previewModal{{ $plantilla->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="fas fa-eye"></i> Vista Previa: {{ $plantilla->nombre }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if($plantilla->tipo == 'email' && $plantilla->asunto)
                                                <div class="mb-3">
                                                    <strong>Asunto:</strong>
                                                    <p class="p-2 bg-light rounded">{{ $plantilla->asunto }}</p>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>Contenido:</strong>
                                                <div class="p-3 bg-light rounded" style="white-space: pre-wrap;">{{ $plantilla->contenido }}</div>
                                            </div>
                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle"></i>
                                                    Las variables dinámicas se reemplazarán automáticamente al usar la plantilla
                                                </small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <a href="{{ route('admin.plantillas.edit', $plantilla) }}" class="btn btn-primary">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-3">
                {{ $plantillas->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-4x text-muted mb-3 opacity-50"></i>
                <h5 class="text-muted">No hay plantillas disponibles</h5>
                <p class="text-muted">Crea tu primera plantilla para comenzar</p>
                <a href="{{ route('admin.plantillas.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus"></i> Crear Plantilla
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Variables Disponibles -->
<div class="card mt-4">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="fas fa-code"></i> Variables Dinámicas Disponibles</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <ul class="list-unstyled small">
                    <li><code>{nombre_cliente}</code> - Nombre del cliente</li>
                    <li><code>{email_cliente}</code> - Email del cliente</li>
                    <li><code>{telefono_cliente}</code> - Teléfono del cliente</li>
                    <li><code>{empresa}</code> - Nombre de la empresa</li>
                </ul>
            </div>
            <div class="col-md-4">
                <ul class="list-unstyled small">
                    <li><code>{servicio}</code> - Nombre del servicio</li>
                    <li><code>{estado}</code> - Estado de la solicitud</li>
                    <li><code>{monto_cotizado}</code> - Monto cotizado (CLP)</li>
                    <li><code>{fecha_estimada}</code> - Fecha estimada de entrega</li>
                </ul>
            </div>
            <div class="col-md-4">
                <ul class="list-unstyled small">
                    <li><code>{solicitud_id}</code> - ID de la solicitud</li>
                    <li><code>{fecha_actual}</code> - Fecha actual</li>
                    <li><code>{año_actual}</code> - Año actual</li>
                </ul>
            </div>
        </div>
        <small class="text-muted">
            <i class="fas fa-lightbulb"></i>
            <strong>Tip:</strong> Usa estas variables en tus plantillas y se reemplazarán automáticamente con los datos reales
        </small>
    </div>
</div>
@endsection
