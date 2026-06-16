@extends('layouts.public')

@section('title', 'Pago Fallido')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-body text-center py-5">
                    <div class="text-danger mb-4">
                        <i class="fas fa-times-circle" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="text-danger mb-3">Pago No Realizado</h2>
                    <p class="lead">Hubo un problema al procesar tu pago.</p>
                    
                    <div class="bg-light p-4 rounded my-4">
                        <p class="text-muted mb-0">{{ $error }}</p>
                    </div>
                    
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home"></i> Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
