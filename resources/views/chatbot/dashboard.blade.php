<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HateaChistopher - Chatbot</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{background:linear-gradient(135deg,#0f0c29,#302b63,#24243e);min-height:100vh;font-family:system-ui,-apple-system,sans-serif;color:#fff;display:flex;height:100vh;overflow:hidden}

        /* Sidebar */
        .sidebar{width:280px;background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);border-right:1px solid rgba(255,255,255,0.1);display:flex;flex-direction:column}
        .sidebar-header{padding:20px;border-bottom:1px solid rgba(255,255,255,0.1)}
        .sidebar-header h1{font-size:20px;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:5px}
        .sidebar-header .plan{font-size:12px;color:rgba(255,255,255,0.5)}
        .new-chat-btn{margin:15px;padding:12px;background:linear-gradient(135deg,#667eea,#764ba2);border:none;border-radius:10px;color:#fff;font-weight:600;cursor:pointer;transition:transform 0.2s}
        .new-chat-btn:hover{transform:translateY(-2px);box-shadow:0 5px 15px rgba(102,126,234,0.4)}
        .conversations{flex:1;overflow-y:auto;padding:10px}
        .conversation-item{padding:12px;margin-bottom:5px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.05);border-radius:8px;cursor:pointer;transition:all 0.2s}
        .conversation-item:hover,.conversation-item.active{background:rgba(255,255,255,0.08);border-color:rgba(102,126,234,0.3)}
        .conversation-item-title{font-size:14px;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .conversation-item-date{font-size:11px;color:rgba(255,255,255,0.4)}
        .sidebar-footer{padding:15px;border-top:1px solid rgba(255,255,255,0.1)}
        .user-info{display:flex;justify-content:space-between;align-items:center;font-size:13px;color:rgba(255,255,255,0.7)}
        .logout-btn{background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);padding:6px 12px;border-radius:6px;font-size:12px;color:#fff;cursor:pointer;transition:all 0.2s}
        .logout-btn:hover{background:rgba(255,255,255,0.15)}

        /* Main Chat Area */
        .main{flex:1;display:flex;flex-direction:column}
        .chat-header{padding:20px;background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);border-bottom:1px solid rgba(255,255,255,0.1);display:flex;justify-content:space-between;align-items:center}
        .chat-header h2{font-size:18px}
        .chat-stats{display:flex;gap:20px;font-size:13px;color:rgba(255,255,255,0.6)}
        .chat-messages{flex:1;overflow-y:auto;padding:30px;display:flex;flex-direction:column;gap:20px}
        .message{max-width:70%;padding:15px 20px;border-radius:15px;line-height:1.6}
        .message.user{align-self:flex-end;background:linear-gradient(135deg,#667eea,#764ba2);margin-left:auto}
        .message.bot{align-self:flex-start;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.1)}
        .message-time{font-size:11px;color:rgba(255,255,255,0.4);margin-top:5px}
        .chat-input-container{padding:20px;background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);border-top:1px solid rgba(255,255,255,0.1)}
        .chat-input-wrapper{display:flex;gap:10px;max-width:900px;margin:0 auto}
        .chat-input{flex:1;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);border-radius:12px;padding:15px 20px;color:#fff;font-size:15px;resize:none;min-height:50px;max-height:150px}
        .chat-input:focus{outline:none;background:rgba(255,255,255,0.12);border-color:#667eea}
        .chat-input::placeholder{color:rgba(255,255,255,0.4)}
        .send-btn{background:linear-gradient(135deg,#667eea,#764ba2);border:none;border-radius:12px;padding:0 30px;color:#fff;font-weight:600;cursor:pointer;transition:transform 0.2s}
        .send-btn:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 5px 15px rgba(102,126,234,0.4)}
        .send-btn:disabled{opacity:0.5;cursor:not-allowed}
        .empty-state{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:rgba(255,255,255,0.5);text-align:center}
        .empty-state-icon{font-size:64px;margin-bottom:20px;opacity:0.3}
        .empty-state h3{font-size:24px;margin-bottom:10px;color:rgba(255,255,255,0.7)}
        .empty-state p{font-size:16px}

        @media (max-width: 768px){
            .sidebar{width:70px}
            .sidebar-header h1,.conversation-item-title,.user-info span,.plan,.new-chat-btn{display:none}
            .message{max-width:90%}
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h1>🤖 HateaChistopher</h1>
            <div class="plan">Plan: {{ ucfirst($user->plan) }}</div>
        </div>

        <button class="new-chat-btn" onclick="nuevaConversacion()">➕ Nueva Conversación</button>

        <div class="conversations">
            @if($conversacionesRecientes->count() > 0)
                @foreach($conversacionesRecientes as $conv)
                    <div class="conversation-item" onclick="cargarConversacion({{ $conv->id }})">
                        <div class="conversation-item-title">{{ $conv->titulo ?? 'Nueva conversación' }}</div>
                        <div class="conversation-item-date">{{ $conv->created_at->diffForHumans() }}</div>
                    </div>
                @endforeach
            @else
                <div style="padding:20px;text-align:center;color:rgba(255,255,255,0.4);font-size:13px">
                    No hay conversaciones
                </div>
            @endif
        </div>

        <div class="sidebar-footer">
            <div class="user-info">
                <span>👋 {{ $user->name }}</span>
                <form method="POST" action="{{ route('chatbot.logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="logout-btn">Salir</button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Chat Area -->
    <main class="main">
        <header class="chat-header">
            <h2>💬 Chat</h2>
            <div class="chat-stats">
                <span>📊 {{ $roastsDisponibles }} roasts disponibles</span>
                <span>💬 {{ $totalConversaciones }} conversaciones</span>
            </div>
        </header>

        <div class="chat-messages" id="chatMessages">
            <div class="empty-state">
                <div class="empty-state-icon">💬</div>
                <h3>¡Comienza una conversación!</h3>
                <p>Escribe un mensaje abajo para que HateaChistopher te responda</p>
            </div>
        </div>

        <div class="chat-input-container">
            <div class="chat-input-wrapper">
                <textarea
                    class="chat-input"
                    id="messageInput"
                    placeholder="Escribe tu mensaje... (HateaChistopher te dirá las cosas como son)"
                    onkeydown="handleKeyPress(event)"
                ></textarea>
                <button class="send-btn" onclick="enviarMensaje()" id="sendBtn">Enviar</button>
            </div>
        </div>
    </main>

    <script>
        let conversacionActual = null;

        function nuevaConversacion() {
            fetch("{{ route('chatbot.api.nueva-conversacion') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                conversacionActual = data.conversacion_id;
                document.getElementById('chatMessages').innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">✨</div>
                        <h3>Nueva conversación iniciada</h3>
                        <p>Escribe tu primer mensaje</p>
                    </div>
                `;
                document.getElementById('messageInput').focus();
                // Recargar para actualizar sidebar
                setTimeout(() => location.reload(), 1000);
            });
        }

        function cargarConversacion(id) {
            conversacionActual = id;
            fetch(`{{ url('/app/api/conversacion') }}/${id}`)
            .then(res => res.json())
            .then(data => {
                const messagesDiv = document.getElementById('chatMessages');
                messagesDiv.innerHTML = '';
                data.mensajes.forEach(msg => {
                    agregarMensajeAlChat(msg.contenido, msg.es_usuario);
                });
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            });
        }

        function enviarMensaje() {
            const input = document.getElementById('messageInput');
            const mensaje = input.value.trim();
            if (!mensaje) return;

            const sendBtn = document.getElementById('sendBtn');
            sendBtn.disabled = true;

            // Si no hay conversación activa, crear una
            if (!conversacionActual) {
                nuevaConversacion();
                setTimeout(() => enviarMensajeReal(mensaje), 1500);
            } else {
                enviarMensajeReal(mensaje);
            }

            input.value = '';
        }

        function enviarMensajeReal(mensaje) {
            const messagesDiv = document.getElementById('chatMessages');
            if (messagesDiv.querySelector('.empty-state')) {
                messagesDiv.innerHTML = '';
            }

            agregarMensajeAlChat(mensaje, true);

            fetch("{{ route('chatbot.api.enviar-mensaje') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    conversacion_id: conversacionActual,
                    mensaje: mensaje
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.respuesta) {
                    agregarMensajeAlChat(data.respuesta, false);
                }
                document.getElementById('sendBtn').disabled = false;
                document.getElementById('messageInput').focus();
            })
            .catch(() => {
                agregarMensajeAlChat('Error al enviar mensaje. Intenta de nuevo.', false);
                document.getElementById('sendBtn').disabled = false;
            });
        }

        function agregarMensajeAlChat(texto, esUsuario) {
            const messagesDiv = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${esUsuario ? 'user' : 'bot'}`;
            messageDiv.innerHTML = `
                ${texto}
                <div class="message-time">${new Date().toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}</div>
            `;
            messagesDiv.appendChild(messageDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function handleKeyPress(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                enviarMensaje();
            }
        }
    </script>
</body>
</html>
