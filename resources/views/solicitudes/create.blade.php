@extends('layouts.public')

@section('title', 'Solicitar Cotización - MaxiSolutions')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="mb-4">Solicita una Cotización</h2>
            
            <form action="{{ route('solicitud.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre_cliente" class="form-control" required value="{{ old('nombre_cliente') }}">
                        @error('nombre_cliente')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email_cliente" class="form-control" required value="{{ old('email_cliente') }}">
                        @error('email_cliente')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" name="telefono_cliente" class="form-control" value="{{ old('telefono_cliente') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Empresa</label>
                        <input type="text" name="empresa" class="form-control" value="{{ old('empresa') }}">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Servicio de Interés</label>
                    <select class="form-select" name="servicio_id">
                        <option value="">Selecciona un servicio (opcional)</option>
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}" {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>
                                {{ $servicio->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Descripción del Proyecto *</label>
                    <textarea name="descripcion_proyecto" class="form-control" rows="5" required>{{ old('descripcion_proyecto') }}</textarea>
                    @error('descripcion_proyecto')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Presupuesto Estimado</label>
                    <input type="text" name="presupuesto_estimado" class="form-control" placeholder="Ej: $500 - $1000" value="{{ old('presupuesto_estimado') }}">
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-paper-plane"></i> Enviar Solicitud
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
