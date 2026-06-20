<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAES - Práctica</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .pregunta-container { min-height: 400px; }
        .alternativa-card { cursor: pointer; transition: all 0.3s ease; border: 2px solid transparent; }
        .alternativa-card:hover { border-color: #0d6efd; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .alternativa-card.selected { border-color: #0d6efd; background-color: #e7f1ff; }
        .alternativa-card.correcta { border-color: #198754; background-color: #d1e7dd; }
        .alternativa-card.incorrecta { border-color: #dc3545; background-color: #f8d7da; }
        .alternativa-card.disabled { cursor: not-allowed; opacity: 0.6; }
        .timer { font-size: 1.5rem; font-weight: bold; }
        .progress-tracker { display: flex; gap: 5px; flex-wrap: wrap; }
        .progress-dot { width: 12px; height: 12px; border-radius: 50%; background-color: #e9ecef; transition: all 0.3s ease; }
        .progress-dot.answered { background-color: #0d6efd; }
        .progress-dot.current { border: 2px solid #0d6efd; width: 16px; height: 16px; }
        .explicacion-box { background-color: #f8f9fa; border-left: 4px solid #0d6efd; padding: 1rem; margin-top: 1rem; display: none; }
        .explicacion-box.show { display: block; }
    </style>
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('paes.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('paes.practica') }}">Practicar</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('paes.simulador') }}">Simulador</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('paes.estadisticas') }}">Estadísticas</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->name ?? 'Usuario' }}
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
        <div id="config-section">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-body p-5">
                            <h2 class="text-center mb-4"><i class="fas fa-dumbbell text-primary"></i> Configurar Práctica</h2>
                            <form id="config-form">
                                <div class="mb-4">
                                    <label for="materia_id" class="form-label">Materia</label>
                                    <select class="form-select form-select-lg" id="materia_id" name="materia_id" required>
                                        <option value="">Selecciona una materia</option>
                                        @foreach($materias as $materia)
                                            <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="tema_id" class="form-label">Tema (opcional)</label>
                                    <select class="form-select form-select-lg" id="tema_id" name="tema_id">
                                        <option value="">Todos los temas</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="cantidad" class="form-label">Cantidad de preguntas</label>
                                    <select class="form-select form-select-lg" id="cantidad" name="cantidad" required>
                                        <option value="5">5 preguntas</option>
                                        <option value="10" selected>10 preguntas</option>
                                        <option value="15">15 preguntas</option>
                                        <option value="20">20 preguntas</option>
                                        <option value="30">30 preguntas</option>
                                        <option value="50">50 preguntas</option>
                                    </select>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-play"></i> Comenzar Práctica
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="practice-section" style="display: none;">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <div class="timer" id="timer">00:00</div>
                            <small class="text-muted">Tiempo transcurrido</small>
                        </div>
                        <div class="col-md-6">
                            <div class="progress-tracker" id="progress-tracker"></div>
                        </div>
                        <div class="col-md-3 text-center">
                            <h4 id="question-counter">1/10</h4>
                            <small class="text-muted">Pregunta actual</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card pregunta-container mb-4">
                <div class="card-body p-4">
                    <h4 class="mb-4" id="pregunta-texto"></h4>
                    <div id="alternativas-container" class="row g-3"></div>
                    <div class="explicacion-box" id="explicacion-box">
                        <h6><i class="fas fa-lightbulb"></i> Explicación</h6>
                        <p id="explicacion-texto"></p>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-outline-secondary" id="btn-previous" disabled>
                    <i class="fas fa-chevron-left"></i> Anterior
                </button>
                <button class="btn btn-primary" id="btn-next" style="display: none;">
                    Siguiente <i class="fas fa-chevron-right"></i>
                </button>
                <button class="btn btn-success" id="btn-finish" style="display: none;">
                    <i class="fas fa-flag-checkered"></i> Finalizar
                </button>
            </div>
        </div>

        <div id="results-section" style="display: none;">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-body p-5 text-center">
                            <h2 class="mb-4"><i class="fas fa-trophy text-warning"></i> ¡Práctica Completada!</h2>
                            <div class="row g-4 mb-5">
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h3 class="mb-0" id="result-total">0</h3>
                                            <p class="text-muted mb-0">Total</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h3 class="mb-0" id="result-correctas">0</h3>
                                            <p class="mb-0">Correctas</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body">
                                            <h3 class="mb-0" id="result-incorrectas">0</h3>
                                            <p class="mb-0">Incorrectas</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body">
                                            <h3 class="mb-0" id="result-porcentaje">0%</h3>
                                            <p class="mb-0">Acierto</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-3">
                                <a href="{{ route('paes.practica') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-redo"></i> Practicar de nuevo
                                </a>
                                <a href="{{ route('paes.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-home"></i> Volver al dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let state = {sesionId:null,preguntas:[],currentIndex:0,respuestas:{},timerInterval:null,startTime:null};
        document.getElementById('materia_id').addEventListener('change',async function(){const mid=this.value,ts=document.getElementById('tema_id');ts.innerHTML='<option value="">Todos los temas</option>';if(!mid)return;try{const r=await fetch('/app/api/materias/${mid}/temas'),t=await r.json();t.forEach(tema=>{const o=document.createElement('option');o.value=tema.id;o.textContent=tema.nombre;ts.appendChild(o)})}catch(e){console.error('Error:',e)}});
        document.getElementById('config-form').addEventListener('submit',async function(e){e.preventDefault();const fd={materia_id:document.getElementById('materia_id').value,tema_id:document.getElementById('tema_id').value||null,cantidad:parseInt(document.getElementById('cantidad').value)};try{const r=await fetch('/app/api/preguntas/iniciar',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},body:JSON.stringify(fd)}),d=await r.json();if(!r.ok){alert(d.error||'Error');return}state.sesionId=d.sesion_id;state.preguntas=d.preguntas;state.currentIndex=0;state.respuestas={};state.startTime=Date.now();showPracticeSection();startTimer();renderQuestion()}catch(e){console.error(e);alert('Error')}});
        function showPracticeSection(){document.getElementById('config-section').style.display='none';document.getElementById('practice-section').style.display='block';renderProgressTracker()}
        function renderProgressTracker(){const t=document.getElementById('progress-tracker');t.innerHTML='';state.preguntas.forEach((_,i)=>{const d=document.createElement('div');d.className='progress-dot';if(i===state.currentIndex)d.classList.add('current');if(state.respuestas[i]!==undefined)d.classList.add('answered');t.appendChild(d)})}
        function renderQuestion(){const p=state.preguntas[state.currentIndex],qc=document.getElementById('question-counter'),qt=document.getElementById('pregunta-texto'),ac=document.getElementById('alternativas-container'),eb=document.getElementById('explicacion-box');qc.textContent='${state.currentIndex+1}/${state.preguntas.length}';qt.textContent=p.enunciado;ac.innerHTML='';eb.classList.remove('show');const r=state.respuestas[state.currentIndex],yr=r!==undefined;p.alternativas.forEach(alt=>{const col=document.createElement('div');col.className='col-12';const card=document.createElement('div');card.className='alternativa-card card p-3';if(yr){card.classList.add('disabled');if(r.alternativa_id===alt.id)card.classList.add(r.correcta?'correcta':'incorrecta');if(r.alternativa_correcta_id===alt.id)card.classList.add('correcta')}card.innerHTML='<div class="d-flex align-items-start"><div class="me-3"><strong>'+alt.letra+')</strong></div><div class="flex-grow-1">'+alt.texto+'</div></div>';if(!yr)card.addEventListener('click',()=>responderPregunta(alt.id));col.appendChild(card);ac.appendChild(col)});if(yr&&r.explicacion){document.getElementById('explicacion-texto').textContent=r.explicacion;eb.classList.add('show')}updateNavigationButtons();renderProgressTracker()}
        async function responderPregunta(aid){const p=state.preguntas[state.currentIndex],tr=Math.floor((Date.now()-state.startTime)/1000);try{const r=await fetch('/app/api/preguntas/responder',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},body:JSON.stringify({sesion_id:state.sesionId,pregunta_id:p.id,alternativa_id:aid,tiempo:tr})}),d=await r.json();if(!r.ok){alert(d.error||'Error');return}state.respuestas[state.currentIndex]={alternativa_id:aid,correcta:d.correcta,alternativa_correcta_id:d.alternativa_correcta_id,explicacion:d.explicacion};renderQuestion()}catch(e){console.error(e);alert('Error')}}
        function updateNavigationButtons(){const bp=document.getElementById('btn-previous'),bn=document.getElementById('btn-next'),bf=document.getElementById('btn-finish');bp.disabled=state.currentIndex===0;const yr=state.respuestas[state.currentIndex]!==undefined,eu=state.currentIndex===state.preguntas.length-1;if(yr&&!eu){bn.style.display='block';bf.style.display='none'}else if(yr&&eu){bn.style.display='none';bf.style.display='block'}else{bn.style.display='none';bf.style.display='none'}}
        document.getElementById('btn-previous').addEventListener('click',()=>{if(state.currentIndex>0){state.currentIndex--;renderQuestion()}});
        document.getElementById('btn-next').addEventListener('click',()=>{if(state.currentIndex<state.preguntas.length-1){state.currentIndex++;renderQuestion()}});
        document.getElementById('btn-finish').addEventListener('click',finalizarSesion);
        async function finalizarSesion(){if(!confirm('¿Finalizar?'))return;try{const r=await fetch('/app/api/sesion/${state.sesionId}/finalizar',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}}),d=await r.json();if(!r.ok){alert(d.error||'Error');return}stopTimer();showResults(d.resultados)}catch(e){console.error(e);alert('Error')}}
        function showResults(res){document.getElementById('practice-section').style.display='none';document.getElementById('results-section').style.display='block';document.getElementById('result-total').textContent=res.total;document.getElementById('result-correctas').textContent=res.correctas;document.getElementById('result-incorrectas').textContent=res.incorrectas;document.getElementById('result-porcentaje').textContent=Math.round(res.porcentaje)+'%'}
        function startTimer(){state.timerInterval=setInterval(()=>{const e=Math.floor((Date.now()-state.startTime)/1000),m=Math.floor(e/60),s=e%60;document.getElementById('timer').textContent=String(m).padStart(2,'0')+':'+String(s).padStart(2,'0')},1000)}
        function stopTimer(){if(state.timerInterval){clearInterval(state.timerInterval);state.timerInterval=null}}
    </script>
</body>
</html>