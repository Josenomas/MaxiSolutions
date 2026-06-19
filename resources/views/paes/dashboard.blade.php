<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAES - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('paes.dashboard') }}">
                <i class="fas fa-graduation-cap"></i> PAES Prep
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('paes.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('paes.practica') }}">Practicar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('paes.simulador') }}">Simulador</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('paes.estadisticas') }}">Estadísticas</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="https://maxisolutions.cl/dashboard">MaxiSolutions</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="https://maxisolutions.cl/logout">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Cerrar sesión</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1>Bienvenido, {{ auth()->user()->name }}!</h1>
                <p class="text-muted">Prepárate para la PAES con nuestro sistema de práctica inteligente</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-clipboard-check fa-3x text-primary mb-3"></i>
                        <h3 class="mb-0">{{ $sesionesCompletadas }}</h3>
                        <p class="text-muted mb-0">Sesiones completadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-fire fa-3x text-danger mb-3"></i>
                        <h3 class="mb-0">{{ array_sum(array_column($progresoMaterias, 'racha_actual')) }}</h3>
                        <p class="text-muted mb-0">Racha actual</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-question-circle fa-3x text-success mb-3"></i>
                        <h3 class="mb-0">{{ array_sum(array_column($progresoMaterias, 'total_preguntas')) }}</h3>
                        <p class="text-muted mb-0">Preguntas resueltas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-trophy fa-3x text-warning mb-3"></i>
                        @php
                            $promedio = count($progresoMaterias) > 0 ? round(array_sum(array_column($progresoMaterias, 'porcentaje_acierto')) / count($progresoMaterias)) : 0;
                        @endphp
                        <h3 class="mb-0">{{ $promedio }}%</h3>
                        <p class="text-muted mb-0">Promedio general</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <h3 class="mb-4">Progreso por Materia</h3>
                <div class="row g-4">
                    @foreach($materias as $materia)
                        @php
                            $progreso = $progresoMaterias[$materia->id] ?? ['total_preguntas' => 0, 'porcentaje_acierto' => 0, 'racha_actual' => 0];
                        @endphp
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $materia->nombre }}</h5>
                                    <div class="mb-3">
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: {{ $progreso['porcentaje_acierto'] }}%;"
                                                 aria-valuenow="{{ $progreso['porcentaje_acierto'] }}"
                                                 aria-valuemin="0" aria-valuemax="100">
                                                {{ round($progreso['porcentaje_acierto']) }}%
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-1"><i class="fas fa-question"></i> {{ $progreso['total_preguntas'] }} preguntas</p>
                                    <p class="mb-0"><i class="fas fa-fire text-danger"></i> Racha: {{ $progreso['racha_actual'] }} días</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-dumbbell fa-3x text-primary mb-3"></i>
                        <h5>Modo Práctica</h5>
                        <p class="text-muted">Practica por materia y tema específico</p>
                        <a href="{{ route('paes.practica') }}" class="btn btn-primary">Practicar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-file-alt fa-3x text-success mb-3"></i>
                        <h5>Simulador PAES</h5>
                        <p class="text-muted">Simula un examen completo oficial</p>
                        <a href="{{ route('paes.simulador') }}" class="btn btn-success">Simular</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                        <h5>Mis Estadísticas</h5>
                        <p class="text-muted">Analiza tu rendimiento y progreso</p>
                        <a href="{{ route('paes.estadisticas') }}" class="btn btn-info">Ver stats</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
