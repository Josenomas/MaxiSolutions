@extends('layouts.canela')

@section('title', 'Productos')
@section('page-title', 'Inventario de Productos')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('canela.productos.create') }}" class="btn btn-canela">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>
    </div>
</div>

<div class="table-card">
    <div class="table-card-header">
        <h3 class="table-card-title">Listado de Productos</h3>
        <span class="badge bg-secondary">{{ $productos->total() }} productos</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Precio Venta</th>
                    <th>Precio Costo</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td>
                            <div class="fw-bold">{{ $producto->nombre }}</div>
                            @if($producto->descripcion)
                                <small class="text-muted">{{ Str::limit($producto->descripcion, 50) }}</small>
                            @endif
                        </td>
                        <td>{{ $producto->categoria->nombre }}</td>
                        <td class="fw-bold text-success">${{ number_format($producto->precio_venta, 0, ',', '.') }}</td>
                        <td class="text-muted">${{ number_format($producto->precio_costo ?? 0, 0, ',', '.') }}</td>
                        <td>
                            @if($producto->stock_bajo)
                                <span class="badge badge-stock-bajo">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $producto->stock }} {{ $producto->unidad }}
                                </span>
                            @else
                                <span class="badge badge-stock-ok">
                                    {{ $producto->stock }} {{ $producto->unidad }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($producto->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('canela.productos.edit', $producto) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('canela.productos.destroy', $producto) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                            No hay productos registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($productos->hasPages())
        <div class="p-3">
            {{ $productos->links() }}
        </div>
    @endif
</div>
@endsection
