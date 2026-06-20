<?php

$file = 'resources/views/paes/landing.blade.php';
$content = file_get_contents($file);

// Reemplazar toda la sección SERVICIOS con FUNCIONALIDADES
$servicios_section = <<<'FUNCIONALIDADES'
<!-- =========================== FUNCIONALIDADES =========================== -->
<section id="funcionalidades" class="ms-section" data-screen-label="Funcionalidades">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="ms-eyebrow">Lo que ofrecemos</span>
      <h2 class="mt-2 mb-3">Funcionalidades <span class="ms-grad-text">inteligentes</span></h2>
      <p class="sub">Todo lo que necesitas para alcanzar tu máximo puntaje en la PAES, con tecnología de inteligencia artificial.</p>
    </div>
    <div class="row g-4">
      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card">
          <span class="ms-ribbon">Popular</span>
          <div class="ms-ico">
            <i class="fas fa-brain"></i>
          </div>
          <h5>Preguntas con IA</h5>
          <p class="desc">Miles de preguntas generadas con inteligencia artificial, adaptadas a tu nivel y áreas de mejora.</p>
          <p class="ms-meta mb-3"><i class="fas fa-infinity"></i> Contenido ilimitado</p>
          <a href="{{ route('paes.dashboard') }}" class="ms-link">Practicar ahora <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card">
          <div class="ms-ico">
            <i class="fas fa-comments"></i>
          </div>
          <h5>Tutor Virtual 24/7</h5>
          <p class="desc">Pregunta tus dudas en cualquier momento. La IA te explica conceptos y resuelve ejercicios paso a paso.</p>
          <p class="ms-meta mb-3"><i class="fas fa-robot"></i> Respuestas instantáneas</p>
          <a href="{{ route('paes.dashboard') }}" class="ms-link">Probar tutor <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card">
          <div class="ms-ico">
            <i class="fas fa-clipboard-list"></i>
          </div>
          <h5>Simuladores PAES</h5>
          <p class="desc">Ensayos completos cronometrados que replican fielmente las condiciones del examen oficial.</p>
          <p class="ms-meta mb-3"><i class="fas fa-clock"></i> Tiempo real</p>
          <a href="{{ route('paes.dashboard') }}" class="ms-link">Iniciar ensayo <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card">
          <div class="ms-ico">
            <i class="fas fa-chart-line"></i>
          </div>
          <h5>Análisis Detallado</h5>
          <p class="desc">Estadísticas completas de tu progreso, fortalezas, debilidades y recomendaciones personalizadas.</p>
          <p class="ms-meta mb-3"><i class="fas fa-bullseye"></i> Enfoque personalizado</p>
          <a href="{{ route('paes.dashboard') }}" class="ms-link">Ver estadísticas <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>
FUNCIONALIDADES;

// Reemplazar sección de servicios
$content = preg_replace(
    '/<!-- =+ SERVICIOS =+ -->.*?<\/section>/s',
    $servicios_section,
    $content
);

// Reemplazar toda la sección PRODUCTOS con MATERIAS + PLANES
$materias_planes_section = <<<'MATERIASPLANES'
<!-- ======================== MATERIAS PAES ======================== -->
<section id="materias" class="ms-section" style="background:var(--ms-bg-alt)" data-screen-label="Materias">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="ms-eyebrow">Contenido completo</span>
      <h2 class="mt-2 mb-3">4 Materias <span class="ms-grad-text">PAES</span></h2>
      <p class="sub">Practica todas las áreas del examen con contenido actualizado y alineado al temario oficial.</p>
    </div>
    <div class="row g-4">
      <div class="col-12 col-sm-6 col-md-3 reveal">
        <div class="ms-card h-100 text-center">
          <div class="ms-ico" style="font-size: 3rem; color: #667eea;">
            <i class="fas fa-calculator"></i>
          </div>
          <h5>Matemática M1 y M2</h5>
          <p class="desc">Álgebra, Geometría, Probabilidades, Datos y Funciones</p>
          <p class="ms-price ms-grad-text mb-1">5+ <span>temas</span></p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3 reveal">
        <div class="ms-card h-100 text-center">
          <div class="ms-ico" style="font-size: 3rem; color: #667eea;">
            <i class="fas fa-book-open"></i>
          </div>
          <h5>Competencia Lectora</h5>
          <p class="desc">Comprensión, Vocabulario, Gramática y Tipos de Textos</p>
          <p class="ms-price ms-grad-text mb-1">5+ <span>temas</span></p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3 reveal">
        <div class="ms-card h-100 text-center">
          <div class="ms-ico" style="font-size: 3rem; color: #667eea;">
            <i class="fas fa-flask"></i>
          </div>
          <h5>Ciencias</h5>
          <p class="desc">Biología, Química y Física con enfoque experimental</p>
          <p class="ms-price ms-grad-text mb-1">6+ <span>temas</span></p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3 reveal">
        <div class="ms-card h-100 text-center">
          <div class="ms-ico" style="font-size: 3rem; color: #667eea;">
            <i class="fas fa-landmark"></i>
          </div>
          <h5>Historia y CCSS</h5>
          <p class="desc">Chile, América, Mundo, Economía y Ciencias Sociales</p>
          <p class="ms-price ms-grad-text mb-1">5+ <span>temas</span></p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======================== PLANES Y PRECIOS ======================== -->
<section id="planes" class="ms-section" data-screen-label="Planes">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="ms-eyebrow">Elige tu plan</span>
      <h2 class="mt-2 mb-3">Planes y <span class="ms-grad-text">precios</span></h2>
      <p class="sub">Desde acceso gratuito hasta preparación completa con IA. Elige el plan que mejor se adapte a tus necesidades.</p>
    </div>
    <div class="row g-4 justify-content-center">
      <!-- Plan Gratuito -->
      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card h-100">
          <h5>Gratuito</h5>
          <p class="ms-price ms-grad-text mb-1">$0 <span>/ mes</span></p>
          <p class="desc mb-4">Perfecto para comenzar</p>
          <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> 10 preguntas/día</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> 4 materias PAES</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Estadísticas básicas</li>
            <li style="margin-bottom: 0.5rem; opacity: 0.4;"><i class="fas fa-times" style="margin-right: 8px;"></i> Sin IA</li>
          </ul>
          <a href="{{ route('paes.dashboard') }}" class="ms-btn ms-btn-ghost w-100">Comenzar gratis</a>
        </div>
      </div>

      <!-- Plan Básico -->
      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card h-100">
          <h5>Básico</h5>
          <p class="ms-price ms-grad-text mb-1">$5.990 <span>/ mes</span></p>
          <p class="desc mb-4">Para práctica regular</p>
          <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> 100 preguntas/día</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Preguntas con IA</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> 20 consultas tutor/día</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Análisis detallado</li>
          </ul>
          <a href="{{ route('paes.dashboard') }}" class="ms-btn ms-btn-primary w-100">Suscribirse</a>
        </div>
      </div>

      <!-- Plan Premium -->
      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card h-100" style="border: 2px solid var(--ms-grad-from); position: relative;">
          <span class="ms-ribbon">Recomendado</span>
          <h5>Premium</h5>
          <p class="ms-price ms-grad-text mb-1">$12.990 <span>/ mes</span></p>
          <p class="desc mb-4">Preparación completa</p>
          <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Preguntas ilimitadas</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Tutor IA ilimitado</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Simuladores completos</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Gamificación total</li>
          </ul>
          <a href="{{ route('paes.dashboard') }}" class="ms-btn ms-btn-primary w-100">Suscribirse</a>
        </div>
      </div>

      <!-- Plan Institucional -->
      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card h-100">
          <h5>Institucional</h5>
          <p class="ms-price ms-grad-text mb-1">Consultar <span>precio</span></p>
          <p class="desc mb-4">Para colegios y preuniversitarios</p>
          <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Usuarios ilimitados</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Dashboard admin</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Reportes grupales</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Soporte dedicado</li>
          </ul>
          <a href="https://maxisolutions.cl/solicitud/create" class="ms-btn ms-btn-ghost w-100">Contactar</a>
        </div>
      </div>
    </div>
  </div>
</section>
MATERIASPLANES;

// Reemplazar sección de productos + el style hover-lift
$content = preg_replace(
    '/<!-- =+ PRODUCTOS SAAS =+ -->.*?<\/style>/s',
    $materias_planes_section,
    $content
);

file_put_contents($file, $content);
echo "Secciones FUNCIONALIDADES, MATERIAS y PLANES agregadas correctamente!\n";
