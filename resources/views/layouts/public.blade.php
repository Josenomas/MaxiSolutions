<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MaxiSolutions - Soluciones Tecnológicas')</title>
    
    <!-- Tipografías: Space Grotesk + Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSS Personalizado MaxiSolutions -->
    <link rel="stylesheet" href="{{ asset('css/maxisolutions.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="ms-nav">
      <div class="container">
        <div class="d-flex align-items-center justify-content-between py-3">
          <a href="{{ route('home') }}" class="brand">Maxi<b>Solutions</b></a>

          <!-- Desktop Menu -->
          <div class="d-none d-lg-flex align-items-center gap-4">
            <a href="{{ route('home') }}#servicios" class="link">Servicios</a>
            <a href="{{ route('home') }}#portafolio" class="link">Portafolio</a>
            <a href="{{ route('home') }}" class="link">Inicio</a>
            @auth
              <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="link">Dashboard</a>
            @endauth
          </div>

          <div class="d-flex align-items-center gap-2">
            <!-- Desktop Buttons -->
            <a href="{{ route('solicitud.create') }}" class="ms-btn ms-btn-primary py-2 px-3 d-none d-sm-inline-flex">
              <i class="fas fa-paper-plane"></i> Cotizar
            </a>
            @auth
              <div class="dropdown d-none d-lg-block">
                <button class="ms-btn ms-btn-ghost py-2 px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                  {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                  <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                    </form>
                  </li>
                </ul>
              </div>
            @else
              <a href="{{ route('login') }}" class="ms-btn ms-btn-ghost py-2 px-3 d-none d-lg-inline-flex">Login</a>
            @endauth

            <!-- Mobile Menu Toggle -->
            <button class="ms-mobile-toggle d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
              <i class="fas fa-bars"></i>
            </button>
          </div>
        </div>

        <!-- Mobile Menu -->
        <div class="collapse ms-mobile-menu" id="mobileMenu">
          <div class="py-3">
            <a href="{{ route('home') }}" class="ms-mobile-link">
              <i class="fas fa-home"></i> Inicio
            </a>
            <a href="{{ route('home') }}#servicios" class="ms-mobile-link">
              <i class="fas fa-briefcase"></i> Servicios
            </a>
            <a href="{{ route('home') }}#portafolio" class="ms-mobile-link">
              <i class="fas fa-folder"></i> Portafolio
            </a>
            <a href="{{ route('solicitud.create') }}" class="ms-mobile-link">
              <i class="fas fa-paper-plane"></i> Cotizar
            </a>
            @auth
              <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="ms-mobile-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
              </a>
              <a href="{{ route('profile.edit') }}" class="ms-mobile-link">
                <i class="fas fa-user"></i> Perfil
              </a>
              <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="ms-mobile-link w-100 text-start border-0 bg-transparent">
                  <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </button>
              </form>
            @else
              <a href="{{ route('login') }}" class="ms-mobile-link">
                <i class="fas fa-sign-in-alt"></i> Login
              </a>
              <a href="{{ route('register') }}" class="ms-mobile-link">
                <i class="fas fa-user-plus"></i> Registrarse
              </a>
            @endauth
          </div>
        </div>
      </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="ms-footer">
      <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="brand">Maxi<b>Solutions</b></div>
        <div class="d-flex gap-4">
          <a href="{{ route('home') }}#servicios">Servicios</a>
          <a href="{{ route('home') }}#portafolio">Portafolio</a>
          <a href="{{ route('solicitud.create') }}">Contacto</a>
        </div>
        <div>© {{ date('Y') }} MaxiSolutions. Todos los derechos reservados.</div>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>