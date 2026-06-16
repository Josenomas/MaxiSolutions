@extends('layouts.admin')

@section('title', 'Crear Servicio')
@section('page-title', 'Crear Nuevo Servicio')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.servicios.store') }}" method="POST">
                    @csrf
                    @include('admin.servicios.form')
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Crear Servicio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
