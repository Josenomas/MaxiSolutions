@extends('layouts.admin')

@section('title', 'Crear Plantilla')
@section('page-title', 'Crear Nueva Plantilla')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Formulario de Nueva Plantilla</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.plantillas.store') }}" method="POST">
                    @csrf

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">
                            Nombre de la Plantilla <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre"
                               name="nombre"
                               value="{{ old('nombre') }}"
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
                            <option value="comentario" {{ old('tipo') == 'comentario' ? 'selected' : '' }}>
                                📝 Comentario (se usa en la sección de comentarios de solicitudes)
                            </option>
                            <option value="email" {{ old('tipo') == 'email' ? 'selected' : '' }}>
                                ✉️ Email (se envía por correo electrónico al cliente)
                            </option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Asunto (solo para emails) -->
                    <div class="mb-3" id="asuntoField" style="display: {{ old('tipo') == 'email' ? 'block' : 'none' }};">
                        <label for="asunto" class="form-label fw-bold">
                            Asunto del Email
                        </label>
                        <input type="text"
                               class="form-control @error('asunto') is-invalid @enderror"
                               id="asunto"
                               name="asunto"
                               value="{{ old('asunto') }}"
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
                                  placeholder="Escribe el contenido de la plantilla aquí. Puedes usar variables dinámicas como {nombre_cliente}, {servicio}, etc.">{{ old('contenido') }}</textarea>
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
                                  placeholder="Descripción opcional para uso interno (cuándo usar esta plantilla)">{{ old('descripcion') }}</textarea>
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
                                   {{ old('activa', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="activa">
                                Plantilla Activa
                            </label>
                        </div>
                        <small class="text-muted">Solo las plantillas activas estarán disponibles para usar</small>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Crear Plantilla
                        </button>
                        <a href="{{ route('admin.plantillas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
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

        <!-- Ejemplos -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-lightbulb"></i> Ejemplos</h6>
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Comentario de Bienvenida:</h6>
                <div class="p-2 bg-light rounded mb-3 small" style="font-family: monospace;">
Hola {nombre_cliente}, hemos recibido tu solicitud #{solicitud_id} para {servicio}. Nuestro equipo la revisará pronto.
                </div>

                <h6 class="fw-bold">Email de Cotización:</h6>
                <div class="p-2 bg-light rounded small" style="font-family: monospace;">
Estimado/a {nombre_cliente},

Hemos preparado una cotización para tu proyecto de {servicio} por un monto de {monto_cotizado}.

Fecha estimada de entrega: {fecha_estimada}

Saludos,
Equipo MaxiSolutions
                </div>
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
