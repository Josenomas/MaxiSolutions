@extends('layouts.public')

@section('title', 'Redirigiendo a Webpay...')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="spinner-border text-primary mb-4" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <h3>Redirigiendo a Webpay...</h3>
            <p class="text-muted">Por favor espera mientras te redirigimos a la plataforma de pago segura.</p>
            
            <form id="webpay-form" action="{{ $url }}" method="POST">
                <input type="hidden" name="token_ws" value="{{ $token }}">
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.getElementById('webpay-form').submit();
        }, 1000);
    });
</script>
@endsection
