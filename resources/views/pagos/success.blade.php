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
                        <p class="mb-1"><strong>Solicitud:</strong> #{{ $solicitud->id }}</p>
                        <p class="mb-1"><strong>Servicio:</strong> {{ $solicitud->servicio->nombre ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Monto Pagado:</strong> ${{ number_format($pago->monto, 0, ',', '.') }} CLP</p>
                        <p class="mb-1"><strong>Método de Pago:</strong> {{ strtoupper($pago->metodo_pago) }}</p>
                        <p class="mb-1"><strong>Orden de Compra:</strong> {{ $pago->buy_order ?? $pago->referencia_pago }}</p>
                        <p class="mb-1"><strong>Fecha y Hora:</strong> {{ $pago->fecha_confirmacion ? $pago->fecha_confirmacion->format('d/m/Y H:i') : $pago->created_at->format('d/m/Y H:i') }}</p>
                        @if($pago->response_data && isset($pago->response_data['authorization_code']))
                            <p class="mb-1"><strong>Código de Autorización:</strong> {{ $pago->response_data['authorization_code'] }}</p>
                        @endif
                        @if($pago->referencia_pago)
                            <p class="mb-0"><strong>Referencia:</strong> {{ $pago->referencia_pago }}</p>
                        @endif
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('pago.boleta', $pago) }}" class="btn btn-success btn-lg" target="_blank">
                            <i class="fas fa-file-pdf"></i> Descargar Boleta Electrónica
                        </a>
                        <a href="{{ route('cliente.solicitud.show', $solicitud) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Ver Detalles de Solicitud
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home"></i> Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
