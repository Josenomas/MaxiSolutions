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
        <span class="ms-eyebrow reveal">El chatbot anti-Chistopher</span>
        <h1 class="my-3 reveal">¿Chistopher te cae mal? <span class="ms-grad-text">A mi también</span></h1>
        <p class="lead mb-4 reveal">El único chatbot diseñado para tirarle hate a Chistopher 24/7. Pregúntame lo que sea y te daré las mejores respuestas para molestar, criticar o simplemente hacerle la vida imposible a Chistopher.</p>
        <div class="d-flex flex-wrap gap-3 mb-4 reveal">
          <a href="#planes" class="ms-btn ms-btn-primary"><i class="fas fa-fire"></i> Empezar a hatear</a>
          <a href="{{ route('chatbot.dashboard') }}" class="ms-btn ms-btn-ghost"><i class="fas fa-robot"></i> Chatear gratis</a>
        </div>
        <div class="d-flex flex-wrap gap-2 reveal">
          <span class="ms-chip"><i class="fas fa-fire"></i> 100% Anti-Chistopher</span>
          <span class="ms-chip"><i class="fas fa-laugh"></i> Humor sin límites</span>
          <span class="ms-chip"><i class="fas fa-bomb"></i> Roasts épicos</span>
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
            <span class="ln"><span class="c-key">class</span> <span class="c-fn">RoasterDeChistopher</span> {</span>
            <span class="ln">&nbsp;&nbsp;<span class="c-key">public function</span> <span class="c-fn">roastear</span>() {</span>
            <span class="ln">&nbsp;&nbsp;&nbsp;&nbsp;<span class="c-key">return</span> <span class="c-var">$this</span></span>
            <span class="ln">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-><span class="c-fn">practicar</span>(<span class="c-str">'preguntas IA'</span>)</span>
            <span class="ln">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-><span class="c-fn">destruir</span>(); <span class="c-mut">// 🎓</span></span>
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
        <div class="col-6 col-md-3 ms-stat py-4"><div class="num ms-grad-text" data-count="10000">0</div><div class="lbl">Roasts generados</div></div>
        <div class="col-6 col-md-3 ms-stat py-4"><div class="num ms-grad-text" data-count="5">0</div><div class="lbl">Niveles de hate</div></div>
        <div class="col-6 col-md-3 ms-stat py-4"><div class="num ms-grad-text" data-count="999">0</div><div class="lbl">Formas de molestar</div></div>
        <div class="col-6 col-md-3 ms-stat py-4"><div class="num ms-grad-text" data-count="24" data-suffix="/7">0</div><div class="lbl">Disponibilidad</div></div>
      </div>
    </div>
  </div>
</div>

<!-- =========================== FUNCIONALIDADES =========================== -->
<section id="funcionalidades" class="ms-section" data-screen-label="Funcionalidades">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="ms-eyebrow">¿Por qué usar HateaChistopher?</span>
      <h2 class="mt-2 mb-3">El chatbot más <span class="ms-grad-text">anti-Chistopher</span> del mundo</h2>
      <p class="sub">Porque odiar a Chistopher es más divertido con IA. Respuestas creativas, roasts personalizados y humor garantizado.</p>
    </div>
    <div class="row g-4">
      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card">
          <span class="ms-ribbon">Popular</span>
          <div class="ms-ico">
            <i class="fas fa-fire"></i>
          </div>
          <h5>Roasts Personalizados</h5>
          <p class="desc">Roasts únicos generados con IA especialmente diseñados para molestar a Chistopher de forma creativa y divertida.</p>
          <p class="ms-meta mb-3"><i class="fas fa-infinity"></i> Roasts infinitos</p>
          <a href="{{ route('chatbot.dashboard') }}" class="ms-link">Empezar a roastear <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card">
          <div class="ms-ico">
            <i class="fas fa-laugh-beam"></i>
          </div>
          <h5>Modo Sarcasmo Activado</h5>
          <p class="desc">Respuestas con humor, sarcasmo y un toque de hate. Porque molestar a Chistopher es un arte.</p>
          <p class="ms-meta mb-3"><i class="fas fa-robot"></i> 100% Sarcástico</p>
          <a href="{{ route('chatbot.dashboard') }}" class="ms-link">Activar sarcasmo <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card">
          <div class="ms-ico">
            <i class="fas fa-bomb"></i>
          </div>
          <h5>Generador de Comebacks</h5>
          <p class="desc">Las mejores respuestas para dejar a Chistopher sin palabras. Comebacks épicos para cada situación.</p>
          <p class="ms-meta mb-3"><i class="fas fa-clock"></i> Siempre listo</p>
          <a href="{{ route('chatbot.dashboard') }}" class="ms-link">Ver comebacks <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card">
          <div class="ms-ico">
            <i class="fas fa-grin-squint-tears"></i>
          </div>
          <h5>Humor Garantizado</h5>
          <p class="desc">Porque odiar a Chistopher debe ser divertido. Humor inteligente sin pasarse de la raya.</p>
          <p class="ms-meta mb-3"><i class="fas fa-bullseye"></i> Risa asegurada</p>
          <a href="{{ route('chatbot.dashboard') }}" class="ms-link">Reír ahora <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======================== MATERIAS HateaChistopher ======================== -->
<section id="materias" class="ms-section" style="background:var(--ms-bg-alt)" data-screen-label="Materias">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="ms-eyebrow">Niveles de intensidad</span>
      <h2 class="mt-2 mb-3">Modos de <span class="ms-grad-text">Roast</span></h2>
      <p class="sub">Elige el nivel de hate perfecto para cada ocasión. Desde suave hasta devastador.</p>
    </div>
    <div class="row g-4">
      <div class="col-12 col-sm-6 col-md-3 reveal">
        <div class="ms-card h-100 text-center">
          <div class="ms-ico" style="font-size: 3rem; color: #667eea;">
            <i class="fas fa-smile"></i>
          </div>
          <h5>Modo Suave</h5>
          <p class="desc">Para molestar sin ofender. Sarcasmo ligero y humor amigable.</p>
          <p class="ms-price ms-grad-text mb-1">Nivel 1</p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3 reveal">
        <div class="ms-card h-100 text-center">
          <div class="ms-ico" style="font-size: 3rem; color: #667eea;">
            <i class="fas fa-grin"></i>
          </div>
          <h5>Modo Moderado</h5>
          <p class="desc">Un poco más picante. Roasts creativos con ingenio.</p>
          <p class="ms-price ms-grad-text mb-1">Nivel 2</p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3 reveal">
        <div class="ms-card h-100 text-center">
          <div class="ms-ico" style="font-size: 3rem; color: #667eea;">
            <i class="fas fa-fire-alt"></i>
          </div>
          <h5>Modo Intenso</h5>
          <p class="desc">Aquí se pone serio. Roasts que duelen (pero con amor).</p>
          <p class="ms-price ms-grad-text mb-1">Nivel 3</p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3 reveal">
        <div class="ms-card h-100 text-center">
          <div class="ms-ico" style="font-size: 3rem; color: #667eea;">
            <i class="fas fa-bomb"></i>
          </div>
          <h5>Modo Devastador</h5>
          <p class="desc">Sin piedad. El nivel máximo de hate permitido.</p>
          <p class="ms-price ms-grad-text mb-1">Nivel 4</p>
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
      <p class="sub">Elige cuánto hate necesitas. Desde casual hasta profesional del roast.</p>
    </div>
    <div class="row g-4 justify-content-center">
      <!-- Plan Gratuito -->
      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card h-100">
          <h5>Gratuito</h5>
          <p class="ms-price ms-grad-text mb-1">$0 <span>/ mes</span></p>
          <p class="desc mb-4">Para empezar a molestar</p>
          <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> 50 roasts/día</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Todos los modos</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Roasts básicos</li>
            <li style="margin-bottom: 0.5rem; opacity: 0.4;"><i class="fas fa-times" style="margin-right: 8px;"></i> Modo suave únicamente</li>
          </ul>
          <a href="{{ route('chatbot.dashboard') }}" class="ms-btn ms-btn-ghost w-100">Chatear gratis</a>
        </div>
      </div>

      <!-- Plan Básico -->
      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card h-100">
          <h5>Básico</h5>
          <p class="ms-price ms-grad-text mb-1">$4.990 <span>/ mes</span></p>
          <p class="desc mb-4">Para haters serios</p>
          <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> 500 roasts/día</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Roasts con IA</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Comebacks infinitos</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Modo intenso incluido</li>
          </ul>
          <a href="{{ route('chatbot.dashboard') }}" class="ms-btn ms-btn-primary w-100">Suscribirse</a>
        </div>
      </div>

      <!-- Plan Premium -->
      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card h-100" style="border: 2px solid var(--ms-grad-from); position: relative;">
          <span class="ms-ribbon">Recomendado</span>
          <h5>Premium</h5>
          <p class="ms-price ms-grad-text mb-1">$9.990 <span>/ mes</span></p>
          <p class="desc mb-4">Hate ilimitado</p>
          <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Roasts ilimitados</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Todos los modos de hate</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Generador de roasts pro</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Sarcasmo nivel experto</li>
          </ul>
          <a href="{{ route('chatbot.dashboard') }}" class="ms-btn ms-btn-primary w-100">Suscribirse</a>
        </div>
      </div>

      <!-- Plan Institucional -->
      <div class="col-12 col-sm-6 col-lg-3 reveal">
        <div class="ms-card h-100">
          <h5>Institucional</h5>
          <p class="ms-price ms-grad-text mb-1">Consultar <span>precio</span></p>
          <p class="desc mb-4">Para empresas que odian a Chistopher</p>
          <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Hate empresarial ilimitado</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Panel de control de roasts</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Estadísticas de team hate</li>
            <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: #667eea; margin-right: 8px;"></i> Soporte 24/7</li>
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
        <h2 class="mb-3">¿Listo para empezar a molestar a Chistopher?</h2>
        <p class="lead mb-4" style="color:rgba(255,255,255,.78)">Únete ahora y descubre el poder de la IA para generar los mejores roasts anti-Chistopher. Humor garantizado.</p>
        <a href="{{ route('chatbot.dashboard') }}" class="ms-btn ms-btn-primary btn-lg"><i class="fas fa-robot"></i> Empezar a hatear gratis</a>
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
