@extends('layouts.public')

@section('title', 'Pago Exitoso')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-body text-center py-5">
                    <div class="text-success mb-4">
                        <i class="fas fa-check-circle" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="text-success mb-3">¡Pago Exitoso!</h2>
                    <p class="lead">Tu pago ha sido procesado correctamente.</p>
                    
                    <div class="bg-light p-4 rounded my-4">
                        <h5>Detalles del Pago</h5>
                        <hr>
                        <p class="mb-1"><strong>Monto:</strong> ${{ number_format($pago->monto, 0, ',', '.') }} CLP</p>
                        <p class="mb-1"><strong>Orden:</strong> {{ $pago->buy_order }}</p>
                        <p class="mb-1"><strong>Fecha:</strong> {{ $pago->fecha_confirmacion->format('d/m/Y H:i') }}</p>
                        @if($pago->response_data && isset($pago->response_data['authorization_code']))
                            <p class="mb-0"><strong>Código de Autorización:</strong> {{ $pago->response_data['authorization_code'] }}</p>
                        @endif
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
