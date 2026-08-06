@extends('layouts.canela')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Estadísticas principales -->
<div class="row g-4 mb-4">
    <!-- Ventas del día -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: #fee2e2; color: #991b1b;">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-card-title">Ventas de Hoy</div>
            <div class="stat-card-value">${{ number_format($ventasHoy, 0, ',', '.') }}</div>
            <small class="text-muted">{{ $numeroVentasHoy }} ventas realizadas</small>
        </div>
    </div>

    <!-- Ventas del mes -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: #dbeafe; color: #1e40af;">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-card-title">Ventas del Mes</div>
            <div class="stat-card-value">${{ number_format($ventasMes, 0, ',', '.') }}</div>
            <small class="text-muted">{{ now()->format('F Y') }}</small>
        </div>
    </div>

    <!-- Deudas pendientes -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: #fef3c7; color: #92400e;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-card-title">Deudas Pendientes</div>
            <div class="stat-card-value">${{ number_format($deudasPendientes, 0, ',', '.') }}</div>
            <small class="text-muted">Por cobrar</small>
        </div>
    </div>

    <!-- Stock bajo -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: #fce7f3; color: #9f1239;">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="stat-card-title">Productos Stock Bajo</div>
            <div class="stat-card-value">{{ $productosStockBajo }}</div>
            <small class="text-muted">Requieren reposición</small>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Últimas ventas -->
    <div class="col-12 col-lg-8">
        <div class="table-card">
            <div class="table-card-header">
                <h3 class="table-card-title">Últimas Ventas</h3>
                <a href="{{ route('canela.ventas.index') }}" class="btn btn-sm btn-canela">Ver todas</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Pago</th>
                            <th>Fecha</th>
                            <th>Vendedor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimasVentas as $venta)
                            <tr>
                                <td><a href="{{ route('canela.ventas.show', $venta) }}" class="text-decoration-none">#{{ $venta->id }}</a></td>
                                <td>{{ $venta->cliente?->nombre ?? 'Cliente general' }}</td>
                                <td class="fw-bold">${{ number_format($venta->total_final, 0, ',', '.') }}</td>
                                <td>
                                    @if($venta->tipo_pago === 'efectivo')
                                        <span class="badge bg-success">Efectivo</span>
                                    @elseif($venta->tipo_pago === 'tarjeta')
                                        <span class="badge bg-primary">Tarjeta</span>
                                    @elseif($venta->tipo_pago === 'transferencia')
                                        <span class="badge bg-info">Transferencia</span>
                                    @else
                                        <span class="badge bg-warning">Crédito</span>
                                    @endif
                                </td>
                                <td>{{ $venta->fecha_venta->format('d/m/Y H:i') }}</td>
                                <td>{{ $venta->usuario->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No hay ventas registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Productos más vendidos -->
    <div class="col-12 col-lg-4">
        <div class="table-card">
            <div class="table-card-header">
                <h3 class="table-card-title">Top Productos (30 días)</h3>
            </div>
            <div class="p-3">
                @forelse($productosMasVendidos as $producto)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <div class="fw-bold">{{ $producto->nombre }}</div>
                            <small class="text-muted">{{ $producto->categoria->nombre }}</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary">{{ $producto->total_vendido }}</div>
                            <small class="text-muted">vendidos</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        No hay datos de ventas
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Acciones rápidas -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Acciones Rápidas</h5>
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('canela.ventas.create') }}" class="btn btn-canela w-100 py-3">
                            <i class="fas fa-cash-register d-block mb-2" style="font-size: 2rem;"></i>
                            Nueva Venta
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('canela.productos.create') }}" class="btn btn-outline-secondary w-100 py-3">
                            <i class="fas fa-plus-circle d-block mb-2" style="font-size: 2rem;"></i>
                            Nuevo Producto
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('canela.productos.index') }}" class="btn btn-outline-secondary w-100 py-3">
                            <i class="fas fa-boxes d-block mb-2" style="font-size: 2rem;"></i>
                            Ver Inventario
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('canela.ventas.index') }}" class="btn btn-outline-secondary w-100 py-3">
                            <i class="fas fa-chart-line d-block mb-2" style="font-size: 2rem;"></i>
                            Reportes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
