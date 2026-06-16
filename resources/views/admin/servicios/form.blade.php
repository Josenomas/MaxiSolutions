<div class="mb-3">
    <label class="form-label">Nombre *</label>
    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
           value="{{ old('nombre', $servicio->nombre ?? '') }}" required>
    @error('nombre')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="4">{{ old('descripcion', $servicio->descripcion ?? '') }}</textarea>
    @error('descripcion')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Categoría *</label>
        <select name="categoria" class="form-select @error('categoria') is-invalid @enderror" required>
            <option value="">Seleccionar...</option>
            <option value="desarrollo_web" {{ old('categoria', $servicio->categoria ?? '') == 'desarrollo_web' ? 'selected' : '' }}>Desarrollo Web</option>
            <option value="capacitacion" {{ old('categoria', $servicio->categoria ?? '') == 'capacitacion' ? 'selected' : '' }}>Capacitación</option>
            <option value="consultoria" {{ old('categoria', $servicio->categoria ?? '') == 'consultoria' ? 'selected' : '' }}>Consultoría</option>
            <option value="mantenimiento" {{ old('categoria', $servicio->categoria ?? '') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
            <option value="otro" {{ old('categoria', $servicio->categoria ?? '') == 'otro' ? 'selected' : '' }}>Otro</option>
        </select>
        @error('categoria')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6 mb-3">
        <label class="form-label">Precio Base ($)</label>
        <input type="number" name="precio_base" class="form-control @error('precio_base') is-invalid @enderror" 
               value="{{ old('precio_base', $servicio->precio_base ?? '') }}" step="0.01" min="0">
        @error('precio_base')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Duración Estimada</label>
        <input type="text" name="duracion_estimada" class="form-control" 
               value="{{ old('duracion_estimada', $servicio->duracion_estimada ?? '') }}" 
               placeholder="Ej: 2-4 semanas">
    </div>
    
    <div class="col-md-6 mb-3">
        <label class="form-label">Estado *</label>
        <select name="estado" class="form-select" required>
            <option value="activo" {{ old('estado', $servicio->estado ?? 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ old('estado', $servicio->estado ?? '') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
</div>

<div class="mb-4">
    <div class="form-check">
        <input type="checkbox" name="destacado" class="form-check-input" id="destacado" 
               value="1" {{ old('destacado', $servicio->destacado ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="destacado">
            Marcar como servicio destacado
        </label>
    </div>
</div>
