@extends('layouts.public')

@section('title', 'HateaChistopher Prep - Alcanza tu máximo puntaje en la HateaChistopher')

@section('content')

<!-- ============================== HERO ============================== -->
<section id="inicio" class="ms-hero" data-screen-label="Hero">
  <div class="ms-aurora a1"></div>
  <div class="ms-aurora a2"></div>
  <div class="ms-aurora a3"></div>
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="ms-eyebrow reveal">Tu amigo sincero con IA</span>
        <h1 class="my-3 reveal">El chatbot que <span class="ms-grad-text">dice las cosas</span> como son</h1>
        <p class="lead mb-4 reveal">¿Cansado de respuestas genéricas? HateaChistopher es tu asistente virtual con personalidad: directo, honesto y con un toque de sarcasmo. Respuestas inteligentes sin rodeos.</p>
        <div class="d-flex flex-wrap gap-3 mb-4 reveal">
          <a href="#planes" class="ms-btn ms-btn-primary"><i class="fas fa-fire"></i> Ver planes</a>
          <a href="{{ route('chatbot.dashboard') }}" class="ms-btn ms-btn-ghost"><i class="fas fa-robot"></i> Chatear gratis</a>
        </div>
        <div class="d-flex flex-wrap gap-2 reveal">
          <span class="ms-chip"><i class="fas fa-fire"></i> Sin filtros</span>
          <span class="ms-chip"><i class="fas fa-bolt"></i> Respuestas directas</span>
          <span class="ms-chip"><i class="fas fa-smile-wink"></i> Con humor</span>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="ms-window reveal">
          <div class="bar">
            <span class="dot" style="background:#ff5f56"></span>
            <span class="dot" style="background:#ffbd2e"></span>
            <span class="dot" style="background:#27c93f"></span>
            <small>hateachistopher · chat.js</small>
          </div>
          <div class="ms-code">
            <span class="ln"><span class="c-key">class</span> <span class="c-fn">EstudianteHateaChistopher</span> {</span>
            <span class="ln">&nbsp;&nbsp;<span class="c-key">public function</span> <span class="c-fn">entrenar</span>() {</span>
            <span class="ln">&nbsp;&nbsp;&nbsp;&nbsp;<span class="c-key">return</span> <span class="c-var">$this</span></span>
            <span class="ln">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-><span class="c-fn">practicar</span>(<span class="c-str">'preguntas IA'</span>)</span>
            <span class="ln">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-><span class="c-fn">serUtil</span>(); <span class="c-mut">// 🎓</span></span>
            <span class="ln">&nbsp;&nbsp;}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================= STATS STRIP ========================= -->
<div class="ms-stats-wrap">
  <div class="container">
    <div class="ms-stats reveal">
      <div class="row text-center g-0">
        <div class="col-6 col-md-3 ms-stat py-4"><div class="num ms-grad-text" data-count="10000">0</div><div class="lbl">Conversaciones por día</div></div>
        <div class="col-6 col-md-3 ms-stat py-4"><div class="num ms-grad-text" data-count="4">0</div><div class="lbl">Modos disponibles</div></div>
        <div class="col-6 col-md-3 ms-stat py-4"><div class="num ms-grad-text" data-count="21">0</div><div class="lbl">Respuestas inteligentes</div></div>
        <div class="col-6 col-md-3 ms-stat py-4"><div class="num ms-grad-text" data-count="24" data-suffix="/7">0</div><div class="lbl">Disponibilidad</div></div>
      </div>
    </div>
  </div>
</div>

<!-- =========================== FUNCIONALIDADES =========================== -->
<section id="funcionalidades" class="ms-section" data-screen-label="Funcionalidades">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="ms-eyebrow">Lo que hace diferente a HateaChistopher</span>
      <h2 class="mt-2 mb-3">Un chatbot <span class="ms-grad-text">con actitud</span></h2>
      <p class="sub">No más respuestas aburridas. HateaChistopher tiene personalidad, sentido del humor y te dice las cosas claras.</p>
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
          <a href="{{ route('chatbot.dashboard') }}" class="ms-link">Practicar ahora <i class="fas fa-arrow-right"></i></a>
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
          <a href="{{ route('chatbot.dashboard') }}" class="ms-link">Probar tutor <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card">
          <div class="ms-ico">
            <i class="fas fa-clipboard-list"></i>
          </div>
          <h5>Simuladores HateaChistopher</h5>
          <p class="desc">Ensayos completos cronometrados que replican fielmente las condiciones del examen oficial.</p>
          <p class="ms-meta mb-3"><i class="fas fa-clock"></i> Tiempo real</p>
          <a href="{{ route('chatbot.dashboard') }}" class="ms-link">Iniciar ensayo <i class="fas fa-arrow-right"></i></a>
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
          <a href="{{ route('chatbot.dashboard') }}" class="ms-link">Ver estadísticas <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======================== MATERIAS HateaChistopher ======================== -->
<section id="materias" class="ms-section" style="background:var(--ms-bg-alt)" data-screen-label="Materias">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="ms-eyebrow">Contenido completo</span>
      <h2 class="mt-2 mb-3">4 Materias <span class="ms-grad-text">HateaChistopher</span></h2>
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
          <p class="ms-price ms-grad-text mb-1"><!-- ======================== PRODUCTOS SAAS ======================== -->
<section id="portafolio" class="ms-section" style="background:var(--ms-bg-alt)" data-screen-label="Productos">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="ms-eyebrow">Plataformas SaaS</span>
      <h2 class="mt-2 mb-3">Nuestros <span class="ms-grad-text">productos</span></h2>
      <p class="sub">Soluciones digitales listas para usar. Accede a potentes plataformas diseñadas para tu crecimiento.</p>
    </div>
    <div class="row g-4 justify-content-center">
      @forelse($productos as $producto)
        <div class="col-12 col-sm-6 col-md-4 reveal">
          <a href="{{ $producto->url_base }}" target="_blank" class="text-decoration-none">
            <div class="ms-card h-100 hover-lift" style="cursor: pointer; transition: transform 0.3s ease, box-shadow 0.3s ease;">
              <div class="ms-ico" style="font-size: 3rem;">
                <i class="fas {{ $producto->icono }}"></i>
              </div>
              <h5>{{ $producto->nombre }}</h5>
              <p class="desc">{{ $producto->descripcion }}</p>

              @if($producto->planes->count() > 0)
                <div class="mt-3">
                  @php
                    $planMinimo = $producto->planes->where('precio_mensual', '>', 0)->sortBy('precio_mensual')->first();
                  @endphp
                  @if($planMinimo)
                    <p class="ms-price ms-grad-text mb-1">${{ number_format($planMinimo->precio_mensual, 0, ',', '.') }} <span>/ mes</span></p>
                  @else
                    <p class="ms-price ms-grad-text mb-1">Gratis <span>para empezar</span></p>
                  @endif
                </div>
              @endif

              <div class="mt-3">
                <span class="ms-link">Acceder <i class="fas fa-arrow-right"></i></span>
              </div>
            </div>
          </a>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <p class="text-muted">Próximamente nuevos productos SaaS...</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

<style>
.hover-lift:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 40px rgba(102, 126, 234, 0.3) !important;
}
</style> <span>/ mes</span></p>
          <p class="desc mb-4">Perfecto para comenzar</p>
          <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> 10 preguntas/día</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> 4 materias HateaChistopher</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Estadísticas básicas</li>
            <li style="margin-bottom: 0.5rem; opacity: 0.4;"><i class="fas fa-times" style="margin-right: 8px;"></i> Sin IA</li>
          </ul>
          <a href="{{ route('chatbot.dashboard') }}" class="ms-btn ms-btn-ghost w-100">Chatear gratis</a>
        </div>
      </div>

      <!-- Plan Básico -->
      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card h-100">
          <h5>Básico</h5>
          <p class="ms-price ms-grad-text mb-1">.990 <span>/ mes</span></p>
          <p class="desc mb-4">Para práctica regular</p>
          <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> 100 preguntas/día</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Preguntas con IA</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> 20 consultas tutor/día</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Análisis detallado</li>
          </ul>
          <a href="{{ route('chatbot.dashboard') }}" class="ms-btn ms-btn-primary w-100">Suscribirse</a>
        </div>
      </div>

      <!-- Plan Premium -->
      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card h-100" style="border: 2px solid var(--ms-grad-from); position: relative;">
          <span class="ms-ribbon">Recomendado</span>
          <h5>Premium</h5>
          <p class="ms-price ms-grad-text mb-1">.990 <span>/ mes</span></p>
          <p class="desc mb-4">Preparación completa</p>
          <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Preguntas ilimitadas</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Tutor IA ilimitado</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Simuladores completos</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Gamificación total</li>
          </ul>
          <a href="{{ route('chatbot.dashboard') }}" class="ms-btn ms-btn-primary w-100">Suscribirse</a>
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

<!-- =============================== CTA =============================== -->
<section class="ms-section pt-0">
  <div class="container">
    <div class="ms-cta text-center reveal">
      <div class="aurora x"></div>
      <div class="aurora y"></div>
      <div class="ms-cta-inner mx-auto" style="max-width:640px">
        <h2 class="mb-3">¿Listo para alcanzar tu máximo puntaje?</h2>
        <p class="lead mb-4" style="color:rgba(255,255,255,.78)">Comienza gratis hoy y descubre cómo la IA puede transformar tu preparación para la HateaChistopher.</p>
        <a href="{{ route('chatbot.dashboard') }}" class="ms-btn ms-btn-primary btn-lg"><i class="fas fa-robot"></i> Comenzar ahora gratis</a>
      </div>
    </div>
  </div>
</section>

<!-- ============ JAVASCRIPT INTERACTIVO ============ -->
<script>
document.addEventListener('DOMContentLoaded', function(){
  /* reveal on scroll */
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if(e.isIntersecting){
        e.target.classList.add('is-visible');
        io.unobserve(e.target);
      }
    });
  }, { threshold: 0.14 });
  
  document.querySelectorAll('.reveal').forEach((el, i) => {
    el.style.transitionDelay = (i % 4 * 70) + 'ms';
    io.observe(el);
  });

  /* contadores animados */
  function animateCount(el){
    const target = +el.dataset.count;
    const suffix = el.dataset.suffix || '+';
    const dur = 1400;
    const t0 = performance.now();
    
    (function tick(t){
      const p = Math.min((t - t0) / dur, 1);
      const val = Math.floor((1 - Math.pow(1 - p, 3)) * target);
      el.textContent = val + (p >= 1 ? suffix : '');
      if(p < 1) requestAnimationFrame(tick);
    })(t0);
  }
  
  const cio = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if(e.isIntersecting){
        animateCount(e.target);
        cio.unobserve(e.target);
      }
    });
  }, { threshold: 0.6 });
  
  document.querySelectorAll('[data-count]').forEach(el => cio.observe(el));
});
</script>


@endsection
