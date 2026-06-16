<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Administrativo - MaxiSolutions')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding-top: 20px;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.2);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .stats-card {
            border-radius: 15px;
            transition: transform 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .table-actions .btn {
            margin: 0 2px;
            padding: 5px 10px;
            font-size: 0.875rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="px-3 mb-4">
                    <h4><i class="fas fa-cogs"></i> MaxiSolutions</h4>
                    <small>Panel de Cliente</small>
                </div>
                
                <!-- User Info Card -->
                <div class="px-3 mb-3">
                    <div style="background: rgba(255,255,255,0.1); border-radius: 12px; padding: 15px;">
                        <div style="display: flex; align-items: center; margin-bottom: 10px;">
                            <div style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                <i class="fas fa-user" style="font-size: 1.2rem;"></i>
                            </div>
                            <div style="flex: 1; overflow: hidden;">
                                <div style="font-weight: 600; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->name }}</div>
                                <div style="font-size: 0.75rem; opacity: 0.7;">Cliente</div>
                            </div>
                        </div>
                    </div>
                </div>

                <nav class="nav flex-column px-2">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="{{ route('solicitud.create') }}" class="nav-link">
                        <i class="fas fa-plus-circle"></i> Nueva Solicitud
                    </a>
                    <a href="{{ route('cliente.pagos') }}" class="nav-link {{ request()->routeIs('cliente.pagos') ? 'active' : '' }}">
                        <i class="fas fa-credit-card"></i> Mis Pagos
                    </a>
                    <a href="{{ route('cliente.mensajes') }}" class="nav-link {{ request()->routeIs('cliente.mensajes') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i> Mensajes
                    </a>

                    <hr class="bg-light">
                    
                    <a href="{{ route('home') }}" class="nav-link" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Ver Sitio
                    </a>
                    <a href="{{ route('profile.edit') }}" class="nav-link">
                        <i class="fas fa-user"></i> Mi Perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-start w-100">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </button>
                    </form>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Header -->
                <div class="bg-white border-bottom py-3 px-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                        <div class="text-muted">
                            <i class="fas fa-user"></i> {{ auth()->user()->name }}
                        </div>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="px-4 pb-4">
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
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
