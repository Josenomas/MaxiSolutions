@extends('layouts.admin')

@section('title', 'Editar Servicio')
@section('page-title', 'Editar Servicio')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.servicios.update', $servicio) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.servicios.form')
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Servicio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
