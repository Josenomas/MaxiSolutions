@extends('layouts.public')

@section('title', 'Checkout - Pagar Servicio')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-shopping-cart"></i> Pagar Servicio</h4>
                </div>
                <div class="card-body">
                    <h5>Detalles del Servicio</h5>
                    <hr>
                    
                    <div class="mb-3">
                        <strong>Servicio:</strong><br>
                        {{ $solicitud->servicio->nombre ?? 'Servicio Personalizado' }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Cliente:</strong><br>
                        {{ $solicitud->nombre_cliente }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Email:</strong><br>
                        {{ $solicitud->email_cliente }}
                    </div>
                    
                    <div class="mb-4">
                        <strong>Descripción:</strong><br>
                        <p class="text-muted">{{ $solicitud->descripcion_proyecto }}</p>
                    </div>
                    
                    <hr>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Monto a Pagar (CLP)</label>
                        <input type="number" id="monto" class="form-control form-control-lg"
                               value="{{ old('monto', str_replace(['$', ' ', '-'], '', $solicitud->presupuesto_estimado ?? '10000')) }}"
                               min="50" required>
                        <small class="text-muted">Monto mínimo: $50 CLP</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Selecciona Método de Pago</label>

                        <!-- Webpay Option -->
                        <form action="{{ route('webpay.pay', $solicitud) }}" method="POST" class="mb-3" onsubmit="return syncMonto('webpay')">
                            @csrf
                            <input type="hidden" name="monto" id="monto-webpay">

                            <div class="card payment-option border-2" style="cursor: pointer; transition: all 0.3s;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">
                                                <i class="fas fa-credit-card text-primary"></i> Webpay Plus
                                            </h5>
                                            <small class="text-muted">Pago seguro con Transbank (Tarjetas de crédito/débito)</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-arrow-right"></i> Pagar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Flow Option -->
                        <form action="{{ route('flow.pay', $solicitud) }}" method="POST" onsubmit="return syncMonto('flow')">
                            @csrf
                            <input type="hidden" name="monto" id="monto-flow">

                            <div class="card payment-option border-2" style="cursor: pointer; transition: all 0.3s;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">
                                                <i class="fas fa-wallet text-success"></i> Flow
                                            </h5>
                                            <small class="text-muted">Múltiples medios de pago (Servipag, Webpay, transferencias, etc.)</small>
                                        </div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-arrow-right"></i> Pagar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-shield-alt"></i>
                        <strong>Pago 100% Seguro</strong><br>
                        Ambos métodos de pago utilizan protocolos de seguridad bancaria.
                    </div>

                    <div class="d-grid">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function syncMonto(gateway) {
        const monto = document.getElementById('monto').value;
        
        if (!monto || monto < 50) {
            alert('Por favor ingresa un monto válido (mínimo $50 CLP)');
            return false;
        }
        
        document.getElementById('monto-' + gateway).value = monto;
        return true;
    }

    // Hover effects
    document.querySelectorAll('.payment-option').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
</script>
@endsection
