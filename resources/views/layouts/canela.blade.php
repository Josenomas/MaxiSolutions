<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Canela Masandería') - Sistema de Gestión</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --canela-primary: #d97706;
            --canela-secondary: #92400e;
            --canela-light: #fef3c7;
            --canela-dark: #78350f;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--canela-secondary) 0%, var(--canela-dark) 100%);
            color: white;
            padding: 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-brand i {
            font-size: 2rem;
            color: var(--canela-light);
        }

        .sidebar-brand-text h4 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .sidebar-brand-text small {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section-title {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.6;
            font-weight: 600;
            margin-top: 1rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--canela-light);
        }

        .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: var(--canela-light);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .top-bar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
        }

        .content-area {
            padding: 2rem;
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid var(--canela-primary);
        }

        .stat-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card-title {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .stat-card-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1f2937;
        }

        /* Tables */
        .table-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .table-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-card-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
        }

        /* Buttons */
        .btn-canela {
            background: var(--canela-primary);
            color: white;
            border: none;
        }

        .btn-canela:hover {
            background: var(--canela-secondary);
            color: white;
        }

        /* Badges */
        .badge-stock-bajo {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-stock-ok {
            background: #d1fae5;
            color: #065f46;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-bread-slice"></i>
            <div class="sidebar-brand-text">
                <h4>Canela</h4>
                <small>Masandería</small>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Principal</div>
            <a href="{{ route('canela.dashboard') }}" class="nav-link {{ request()->routeIs('canela.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>

            <div class="nav-section-title">Inventario</div>
            <a href="{{ route('canela.productos.index') }}" class="nav-link {{ request()->routeIs('canela.productos.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span>Productos</span>
            </a>

            <div class="nav-section-title">Operaciones</div>
            <a href="{{ route('canela.ventas.create') }}" class="nav-link {{ request()->routeIs('canela.ventas.create') ? 'active' : '' }}">
                <i class="fas fa-cash-register"></i>
                <span>Nueva Venta</span>
            </a>
            <a href="{{ route('canela.ventas.index') }}" class="nav-link {{ request()->routeIs('canela.ventas.index') || request()->routeIs('canela.ventas.show') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i>
                <span>Historial Ventas</span>
            </a>

            <div class="nav-section-title">Sistema</div>
            <a href="{{ route('home') }}" class="nav-link">
                <i class="fas fa-arrow-left"></i>
                <span>Volver a MaxiSolutions</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </button>
                </form>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
