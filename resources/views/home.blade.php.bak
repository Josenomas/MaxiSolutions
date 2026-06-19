@extends('layouts.public')

@section('title', 'MaxiSolutions - Soluciones Tecnológicas a tu Medida')

@section('content')

<!-- ============================== HERO ============================== -->
<section id="inicio" class="ms-hero" data-screen-label="Hero">
  <div class="ms-aurora a1"></div>
  <div class="ms-aurora a2"></div>
  <div class="ms-aurora a3"></div>
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="ms-eyebrow reveal">Software a tu medida</span>
        <h1 class="my-3 reveal">Soluciones tecnológicas que <span class="ms-grad-text">impulsan tu negocio</span></h1>
        <p class="lead mb-4 reveal">Desarrollo web personalizado, capacitaciones especializadas y consultoría en sistemas. Transformamos tus ideas en realidad.</p>
        <div class="d-flex flex-wrap gap-3 mb-4 reveal">
          <a href="#servicios" class="ms-btn ms-btn-primary"><i class="fas fa-rocket"></i> Ver servicios</a>
          <a href="{{ route('solicitud.create') }}" class="ms-btn ms-btn-ghost"><i class="fas fa-envelope"></i> Cotizar proyecto</a>
        </div>
        <div class="d-flex flex-wrap gap-2 reveal">
          <span class="ms-chip"><i class="fas fa-check-circle"></i> Código a medida</span>
          <span class="ms-chip"><i class="fas fa-shield-halved"></i> Seguro y escalable</span>
          <span class="ms-chip"><i class="fas fa-headset"></i> Soporte continuo</span>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="ms-window reveal">
          <div class="bar">
            <span class="dot" style="background:#ff5f56"></span>
            <span class="dot" style="background:#ffbd2e"></span>
            <span class="dot" style="background:#27c93f"></span>
            <small>maxisolutions · app.php</small>
          </div>
          <div class="ms-code">
            <span class="ln"><span class="c-key">class</span> <span class="c-fn">MaxiSolutions</span> {</span>
            <span class="ln">&nbsp;&nbsp;<span class="c-key">public function</span> <span class="c-fn">build</span>(<span class="c-var">$idea</span>) {</span>
            <span class="ln">&nbsp;&nbsp;&nbsp;&nbsp;<span class="c-key">return</span> <span class="c-var">$idea</span></span>
            <span class="ln">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-><span class="c-fn">diseño</span>(<span class="c-str">'a medida'</span>)</span>
            <span class="ln">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-><span class="c-fn">desplegar</span>(); <span class="c-mut">// 🚀</span></span>
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
        <div class="col-6 col-md-3 ms-stat py-4"><div class="num ms-grad-text" data-count="80">0</div><div class="lbl">Proyectos entregados</div></div>
        <div class="col-6 col-md-3 ms-stat py-4"><div class="num ms-grad-text" data-count="60">0</div><div class="lbl">Clientes satisfechos</div></div>
        <div class="col-6 col-md-3 ms-stat py-4"><div class="num ms-grad-text" data-count="8">0</div><div class="lbl">Años de experiencia</div></div>
        <div class="col-6 col-md-3 ms-stat py-4"><div class="num ms-grad-text" data-count="24" data-suffix="/7">0</div><div class="lbl">Soporte disponible</div></div>
      </div>
    </div>
  </div>
</div>

<!-- =========================== SERVICIOS =========================== -->
<section id="servicios" class="ms-section" data-screen-label="Servicios">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="ms-eyebrow">Lo que hacemos</span>
      <h2 class="mt-2 mb-3">Nuestros <span class="ms-grad-text">servicios</span></h2>
      <p class="sub">Soluciones completas para llevar tu proyecto desde la idea hasta producción, con acompañamiento en cada paso.</p>
    </div>
    <div class="row g-4">
      @forelse($servicios as $index => $servicio)
        <div class="col-12 col-sm-6 col-lg-3 reveal">
          <div class="ms-card">
            @if($servicio->destacado)
              <span class="ms-ribbon">Destacado</span>
            @endif
            <div class="ms-ico">
              <i class="fas {{ getCategoriaIcon($servicio->categoria) }}"></i>
            </div>
            <h5>{{ $servicio->nombre }}</h5>
            <p class="desc">{{ $servicio->descripcion }}</p>
            <p class="ms-price ms-grad-text mb-1">${{ number_format($servicio->precio_base, 0, ',', '.') }} <span>desde</span></p>
            <p class="ms-meta mb-3"><i class="fas fa-clock"></i> {{ $servicio->duracion_estimada ?? 'Por proyecto' }}</p>
            <a href="{{ route('solicitud.create') }}" class="ms-link">Solicitar <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <p class="text-muted">No hay servicios disponibles en este momento.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- =========================== PORTAFOLIO =========================== -->
<section id="portafolio" class="ms-section" style="background:var(--ms-bg-alt)" data-screen-label="Portafolio">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="ms-eyebrow">Nuestro trabajo</span>
      <h2 class="mt-2 mb-3">Portafolio de <span class="ms-grad-text">proyectos</span></h2>
      <p class="sub">Una muestra de las soluciones que hemos construido para nuestros clientes.</p>
    </div>
    <div class="row g-4">
      @forelse($portafolio as $proyecto)
        <div class="col-12 col-sm-6 col-md-4 reveal">
          <div class="ms-proj">
            <div class="ms-thumb {{ !$proyecto->imagen ? 'ph' : '' }}">
              @if($proyecto->imagen)
                <img src="{{ asset('storage/' . $proyecto->imagen) }}" alt="{{ $proyecto->nombre }}">
              @else
                <span>{{ $proyecto->nombre }}</span>
              @endif
              <div class="overlay">
                <div class="ms-tags">
                  @foreach(explode(',', $proyecto->tecnologias) as $tech)
                    <span class="ms-tag">{{ trim($tech) }}</span>
                  @endforeach
                </div>
              </div>
            </div>
            <div class="body">
              <h5>{{ $proyecto->nombre }}</h5>
              <p>{{ $proyecto->descripcion }}</p>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <p class="text-muted">No hay proyectos en el portafolio aún.</p>
        </div>
      @endforelse
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
        <h2 class="mb-3">¿Listo para empezar tu proyecto?</h2>
        <p class="lead mb-4" style="color:rgba(255,255,255,.78)">Cuéntanos tu idea y recibe una cotización personalizada sin compromiso.</p>
        <a href="{{ route('solicitud.create') }}" class="ms-btn ms-btn-primary btn-lg"><i class="fas fa-paper-plane"></i> Solicitar cotización</a>
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

@php
function getCategoriaIcon($categoria) {
    $iconos = [
        'desarrollo_web' => 'fa-laptop-code',
        'capacitacion' => 'fa-chalkboard-teacher',
        'consultoria' => 'fa-handshake',
        'mantenimiento' => 'fa-tools',
        'otro' => 'fa-cogs'
    ];
    return $iconos[$categoria] ?? 'fa-cogs';
}
@endphp
@endsection
