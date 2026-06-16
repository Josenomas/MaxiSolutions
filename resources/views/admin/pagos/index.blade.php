@extends('layouts.admin')

@section('title', 'Gestión de Pagos')
@section('page-title', 'Pagos')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body"><h6>Total Pagos</h6><h2>{{ $stats['total_pagos'] }}</h2></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body"><h6>Aprobados</h6><h2>{{ $stats['pagos_aprobados'] }}</h2></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body"><h6>Pendientes</h6><h2>{{ $stats['pagos_pendientes'] }}</h2></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body"><h6>Monto Total</h6><h2>${{ number_format($stats['monto_total_aprobado'], 0, ',', '.') }}</h2></div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card"><div class="card-body"><h6><i class="fas fa-credit-card text-primary"></i> Webpay</h6><h3>${{ number_format($stats['monto_webpay'], 0, ',', '.') }}</h3></div></div>
    </div>
    <div class="col-md-6">
        <div class="card"><div class="card-body"><h6><i class="fas fa-wallet text-success"></i> Flow</h6><h3>${{ number_format($stats['monto_flow'], 0, ',', '.') }}</h3></div></div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-list"></i> Listado de Pagos</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>#</th><th>Solicitud</th><th>Cliente</th><th>Monto</th><th>Método</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
                <tbody>
                    @forelse($pagos as $pago)
                        <tr>
                            <td>{{ $pago->id }}</td>
                            <td>@if($pago->solicitud)<a href="{{ route('admin.solicitudes.show', $pago->solicitud) }}">#{{ $pago->solicitud->id }}</a>@else N/A @endif</td>
                            <td>{{ $pago->solicitud->nombre_cliente ?? 'N/A' }}</td>
                            <td>${{ number_format($pago->monto, 0, ',', '.') }}</td>
                            <td>@if($pago->metodo_pago == 'webpay')<span class="badge bg-primary"><i class="fas fa-credit-card"></i> WEBPAY</span>@else<span class="badge bg-success"><i class="fas fa-wallet"></i> FLOW</span>@endif</td>
                            <td>@php $badgeColors = ['pendiente' => 'warning', 'aprobado' => 'success', 'rechazado' => 'danger', 'anulado' => 'secondary']; @endphp<span class="badge bg-{{ $badgeColors[$pago->estado] ?? 'secondary' }}">{{ ucfirst($pago->estado) }}</span></td>
                            <td>{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                            <td><a href="{{ route('admin.pagos.show', $pago) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No hay pagos registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $pagos->links() }}</div>
    </div>
</div>
@endsection
