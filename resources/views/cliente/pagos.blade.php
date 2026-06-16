@extends('layouts.client')

@section('title', 'Mis Pagos')
@section('page-title', 'Mis Pagos y Deudas')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100" style="border-left: 4px solid #dc3545;">
            <div class="card-body">
                <h6 class="text-muted">Total Deuda</h6>
                <h2>${{ number_format($totalDeuda, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100" style="border-left: 4px solid #28a745;">
            <div class="card-body">
                <h6 class="text-muted">Total Pagado</h6>
                <h2>${{ number_format($totalPagado, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100" style="border-left: 4px solid #ffc107;">
            <div class="card-body">
                <h6 class="text-muted">Saldo Pendiente</h6>
                <h2>${{ number_format($saldoPendiente, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> Detalle de Solicitudes</h5>
    </div>
    <div class="card-body">
        @if($solicitudes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Servicio</th>
                            <th>Monto Cotizado</th>
                            <th>Pagado</th>
                            <th>Saldo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitudes as $solicitud)
                            @php
                                $pagado = $solicitud->pagos->where('estado', 'completado')->sum('monto');
                                $saldo = ($solicitud->monto_cotizado ?? 0) - $pagado;
                            @endphp
                            <tr>
                                <td><strong>#{{ $solicitud->id }}</strong></td>
                                <td>{{ $solicitud->servicio->nombre ?? 'Personalizado' }}</td>
                                <td>${{ number_format($solicitud->monto_cotizado ?? 0, 0, ',', '.') }}</td>
                                <td>${{ number_format($pagado, 0, ',', '.') }}</td>
                                <td>${{ number_format($saldo, 0, ',', '.') }}</td>
                                <td>
                                    @if($saldo > 0)
                                        <span class="badge bg-warning">Pendiente</span>
                                    @else
                                        <span class="badge bg-success">Pagado</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <p>No tienes solicitudes con deuda</p>
            </div>
        @endif
    </div>
</div>
@endsection