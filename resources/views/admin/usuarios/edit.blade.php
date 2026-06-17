@extends('layouts.admin')

@section('title', 'Editar Usuario')
@section('page-title', 'Editar Usuario')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $usuario->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="tipo_usuario" class="form-label">Tipo de Usuario <span class="text-danger">*</span></label>
                <select class="form-select @error('tipo_usuario') is-invalid @enderror" 
                        id="tipo_usuario" name="tipo_usuario" required>
                    <option value="cliente" {{ old('tipo_usuario', $usuario->tipo_usuario) === 'cliente' ? 'selected' : '' }}>Cliente</option>
                    <option value="admin" {{ old('tipo_usuario', $usuario->tipo_usuario) === 'admin' ? 'selected' : '' }}>Administrador</option>
                </select>
                @error('tipo_usuario')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
