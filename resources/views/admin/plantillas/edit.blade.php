@extends('layouts.admin')

@section('title', 'Editar Plantilla')
@section('page-title', 'Editar Plantilla: ' . $plantilla->nombre)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Formulario de Edición</h5>
                <div class="text-muted small">
                    <i class="fas fa-chart-line"></i> Usada {{ $plantilla->veces_usada }} veces
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.plantillas.update', $plantilla) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">
                            Nombre de la Plantilla <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre"
                               name="nombre"
                               value="{{ old('nombre', $plantilla->nombre) }}"
                               placeholder="Ej: Bienvenida - Solicitud Recibida"
                               required
                               maxlength="100">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Nombre identificativo de la plantilla (máx. 100 caracteres)</small>
                    </div>

                    <!-- Tipo -->
                    <div class="mb-3">
                        <label for="tipo" class="form-label fw-bold">
                            Tipo de Plantilla <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('tipo') is-invalid @enderror"
                                id="tipo"
                                name="tipo"
                                required
                                onchange="toggleAsuntoField()">
                            <option value="">Selecciona un tipo</option>
                            <option value="comentario" {{ old('tipo', $plantilla->tipo) == 'comentario' ? 'selected' : '' }}>
                                📝 Comentario (se usa en la sección de comentarios de solicitudes)
                            </option>
                            <option value="email" {{ old('tipo', $plantilla->tipo) == 'email' ? 'selected' : '' }}>
                                ✉️ Email (se envía por correo electrónico al cliente)
                            </option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Asunto (solo para emails) -->
                    <div class="mb-3" id="asuntoField" style="display: {{ old('tipo', $plantilla->tipo) == 'email' ? 'block' : 'none' }};">
                        <label for="asunto" class="form-label fw-bold">
                            Asunto del Email
                        </label>
                        <input type="text"
                               class="form-control @error('asunto') is-invalid @enderror"
                               id="asunto"
                               name="asunto"
                               value="{{ old('asunto', $plantilla->asunto) }}"
                               placeholder="Ej: Confirmación de Solicitud #{solicitud_id}"
                               maxlength="255">
                        @error('asunto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Asunto del correo electrónico (puedes usar variables dinámicas)</small>
                    </div>

                    <!-- Contenido -->
                    <div class="mb-3">
                        <label for="contenido" class="form-label fw-bold">
                            Contenido <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('contenido') is-invalid @enderror"
                                  id="contenido"
                                  name="contenido"
                                  rows="12"
                                  required
                                  placeholder="Escribe el contenido de la plantilla aquí. Puedes usar variables dinámicas como {nombre_cliente}, {servicio}, etc.">{{ old('contenido', $plantilla->contenido) }}</textarea>
                        @error('contenido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Usa variables dinámicas entre llaves para personalizar el mensaje
                        </small>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-bold">Descripción Interna</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                  id="descripcion"
                                  name="descripcion"
                                  rows="2"
                                  placeholder="Descripción opcional para uso interno (cuándo usar esta plantilla)">{{ old('descripcion', $plantilla->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Ayuda a identificar cuándo utilizar esta plantilla</small>
                    </div>

                    <!-- Estado -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   id="activa"
                                   name="activa"
                                   value="1"
                                   {{ old('activa', $plantilla->activa) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="activa">
                                Plantilla Activa
                            </label>
                        </div>
                        <small class="text-muted">Solo las plantillas activas estarán disponibles para usar</small>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <a href="{{ route('admin.plantillas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="button"
                                class="btn btn-outline-danger ms-auto"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i> Eliminar Plantilla
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Información de la Plantilla</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Creada:</strong> {{ $plantilla->created_at->format('d/m/Y H:i') }}</p>
                        <p class="mb-0"><strong>Última actualización:</strong> {{ $plantilla->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Veces usada:</strong> {{ $plantilla->veces_usada }}</p>
                        <p class="mb-0">
                            <strong>Estado:</strong>
                            @if($plantilla->activa)
                                <span class="badge bg-success">✅ Activa</span>
                            @else
                                <span class="badge bg-danger">❌ Inactiva</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar con ayuda -->
    <div class="col-lg-4">
        <!-- Variables Disponibles -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-code"></i> Variables Dinámicas</h6>
            </div>
            <div class="card-body">
                <p class="small mb-3">Copia y pega estas variables en tu contenido:</p>
                <div class="variable-list">
                    <div class="variable-item mb-2">
                        <button class="btn btn-sm btn-outline-secondary w-100 text-start" onclick="copyVariable('{nombre_cliente}')">
                            <i class="fas fa-copy"></i> <code>{nombre_cliente}</code>
                        </button>
                    </div>
                    <div class="variable-item mb-2">
                        <button class="btn btn-sm btn-outline-secondary w-100 text-start" onclick="copyVariable('{email_cliente}')">
                            <i class="fas fa-copy"></i> <code>{email_cliente}</code>
                        </button>
                    </div>
                    <div class="variable-item mb-2">
                        <button class="btn btn-sm btn-outline-secondary w-100 text-start" onclick="copyVariable('{telefono_cliente}')">
                            <i class="fas fa-copy"></i> <code>{telefono_cliente}</code>
                        </button>
                    </div>
                    <div class="variable-item mb-2">
                        <button class="btn btn-sm btn-outline-secondary w-100 text-start" onclick="copyVariable('{empresa}')">
                            <i class="fas fa-copy"></i> <code>{empresa}</code>
                        </button>
                    </div>
                    <div class="variable-item mb-2">
                        <button class="btn btn-sm btn-outline-secondary w-100 text-start" onclick="copyVariable('{servicio}')">
                            <i class="fas fa-copy"></i> <code>{servicio}</code>
                        </button>
                    </div>
                    <div class="variable-item mb-2">
                        <button class="btn btn-sm btn-outline-secondary w-100 text-start" onclick="copyVariable('{estado}')">
                            <i class="fas fa-copy"></i> <code>{estado}</code>
                        </button>
                    </div>
                    <div class="variable-item mb-2">
                        <button class="btn btn-sm btn-outline-secondary w-100 text-start" onclick="copyVariable('{monto_cotizado}')">
                            <i class="fas fa-copy"></i> <code>{monto_cotizado}</code>
                        </button>
                    </div>
                    <div class="variable-item mb-2">
                        <button class="btn btn-sm btn-outline-secondary w-100 text-start" onclick="copyVariable('{fecha_estimada}')">
                            <i class="fas fa-copy"></i> <code>{fecha_estimada}</code>
                        </button>
                    </div>
                    <div class="variable-item mb-2">
                        <button class="btn btn-sm btn-outline-secondary w-100 text-start" onclick="copyVariable('{solicitud_id}')">
                            <i class="fas fa-copy"></i> <code>{solicitud_id}</code>
                        </button>
                    </div>
                    <div class="variable-item mb-2">
                        <button class="btn btn-sm btn-outline-secondary w-100 text-start" onclick="copyVariable('{fecha_actual}')">
                            <i class="fas fa-copy"></i> <code>{fecha_actual}</code>
                        </button>
                    </div>
                    <div class="variable-item mb-2">
                        <button class="btn btn-sm btn-outline-secondary w-100 text-start" onclick="copyVariable('{año_actual}')">
                            <i class="fas fa-copy"></i> <code>{año_actual}</code>
                        </button>
                    </div>
                </div>
                <div class="alert alert-info mt-3 mb-0">
                    <small>
                        <i class="fas fa-lightbulb"></i>
                        <strong>Tip:</strong> Haz clic en cualquier variable para copiarla al portapapeles
                    </small>
                </div>
            </div>
        </div>

        <!-- Vista Previa -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-eye"></i> Vista Previa Actual</h6>
            </div>
            <div class="card-body">
                @if($plantilla->tipo == 'email' && $plantilla->asunto)
                    <div class="mb-3">
                        <strong>Asunto:</strong>
                        <p class="p-2 bg-light rounded small">{{ $plantilla->asunto }}</p>
                    </div>
                @endif
                <div>
                    <strong>Contenido:</strong>
                    <div class="p-3 bg-light rounded small" style="white-space: pre-wrap; max-height: 300px; overflow-y: auto;">{{ $plantilla->contenido }}</div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Esta es la versión actual guardada
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>¿Estás seguro de que deseas eliminar esta plantilla?</strong></p>
                <p class="mb-0">Esta acción no se puede deshacer. La plantilla <strong>"{{ $plantilla->nombre }}"</strong> será eliminada permanentemente.</p>
                @if($plantilla->veces_usada > 0)
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fas fa-info-circle"></i>
                        Esta plantilla ha sido usada <strong>{{ $plantilla->veces_usada }} veces</strong>.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <form action="{{ route('admin.plantillas.destroy', $plantilla) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Sí, Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle campo de asunto según el tipo
function toggleAsuntoField() {
    const tipoSelect = document.getElementById('tipo');
    const asuntoField = document.getElementById('asuntoField');

    if (tipoSelect.value === 'email') {
        asuntoField.style.display = 'block';
    } else {
        asuntoField.style.display = 'none';
    }
}

// Copiar variable al portapapeles
function copyVariable(variable) {
    navigator.clipboard.writeText(variable).then(() => {
        // Mostrar feedback visual
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');

        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 1500);
    });
}

// Inicializar estado del campo asunto al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    toggleAsuntoField();
});
</script>
@endpush
@endsection
