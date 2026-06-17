@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="min-height: 80vh; align-items: center;">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-lock"></i> Cambio de Contraseña Obligatorio</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Debes cambiar tu contraseña temporal antes de continuar usando el sistema.
                    </div>

                    <form action="{{ route('cambiar-password.update') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="password_actual" class="form-label">Contraseña Actual <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password_actual') is-invalid @enderror" 
                                   id="password_actual" name="password_actual" required>
                            @error('password_actual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required minlength="8">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mínimo 8 caracteres</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required minlength="8">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check"></i> Cambiar Contraseña
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
