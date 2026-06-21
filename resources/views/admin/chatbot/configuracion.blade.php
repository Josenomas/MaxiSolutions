@extends('layouts.admin')

@section('title', 'Configuración Chatbot - Panel Administrativo')
@section('page-title', 'Configuración del Chatbot')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form method="POST" action="{{ route('admin.chatbot.configuracion.update') }}">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Límites de Planes -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-layer-group"></i> Límites por Plan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-gift text-secondary"></i> Plan Gratuito - Mensajes/día
                        </label>
                        <input type="number" name="limite_gratuito" class="form-control" value="{{ old('limite_gratuito', $config['limite_gratuito']) }}" min="1" required>
                        <small class="form-text text-muted">Límite diario de mensajes para usuarios gratuitos</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-star text-warning"></i> Plan Básico - Mensajes/día
                        </label>
                        <input type="number" name="limite_basico" class="form-control" value="{{ old('limite_basico', $config['limite_basico']) }}" min="1" required>
                        <small class="form-text text-muted">Límite diario de mensajes para usuarios básicos</small>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> <strong>Plan Premium:</strong> Sin límites (ilimitado)
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuración de IA -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-brain"></i> Configuración de IA</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Modelo de IA</label>
                        <select name="modelo_default" class="form-select" required>
                            <option value="gpt-3.5-turbo" {{ $config['modelo_default'] === 'gpt-3.5-turbo' ? 'selected' : '' }}>GPT-3.5 Turbo</option>
                            <option value="gpt-4" {{ $config['modelo_default'] === 'gpt-4' ? 'selected' : '' }}>GPT-4</option>
                            <option value="gpt-4-turbo" {{ $config['modelo_default'] === 'gpt-4-turbo' ? 'selected' : '' }}>GPT-4 Turbo</option>
                            <option value="claude-3-sonnet" {{ $config['modelo_default'] === 'claude-3-sonnet' ? 'selected' : '' }}>Claude 3 Sonnet</option>
                            <option value="claude-3-opus" {{ $config['modelo_default'] === 'claude-3-opus' ? 'selected' : '' }}>Claude 3 Opus</option>
                        </select>
                        <small class="form-text text-muted">Modelo de IA utilizado para generar respuestas</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Temperatura (Creatividad)</label>
                        <input type="number" name="temperatura_default" class="form-control" value="{{ old('temperatura_default', $config['temperatura_default']) }}" min="0" max="2" step="0.1" required>
                        <small class="form-text text-muted">0 = Más preciso, 2 = Más creativo (recomendado: 0.7)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Max Tokens</label>
                        <input type="number" name="max_tokens_default" class="form-control" value="{{ old('max_tokens_default', $config['max_tokens_default']) }}" min="1" required>
                        <small class="form-text text-muted">Longitud máxima de las respuestas</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Prompt -->
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-message"></i> Prompt del Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Instrucciones para la IA</label>
                        <textarea name="system_prompt" class="form-control" rows="6" required>{{ old('system_prompt', $config['system_prompt']) }}</textarea>
                        <small class="form-text text-muted">
                            Define cómo debe comportarse el chatbot. Este mensaje es invisible para el usuario.
                        </small>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="fas fa-lightbulb"></i> <strong>Ejemplo:</strong> "Eres un asistente virtual amigable y profesional que ayuda a los usuarios con sus consultas. Responde de manera clara y concisa."
                    </div>
                </div>
            </div>
        </div>

        <!-- API Key -->
        <div class="col-lg-12">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-key"></i> API Key (Confidencial)</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">OpenAI / Anthropic API Key</label>
                        <input type="password" name="api_key" class="form-control" value="{{ old('api_key', $config['api_key'] ? '••••••••••••' : '') }}" placeholder="sk-...">
                        <small class="form-text text-muted">
                            Solo para super admins. Dejar en blanco para mantener la actual.
                        </small>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Importante:</strong> Esta clave se almacena de forma segura en la configuración del servidor.
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="fas fa-save"></i> Guardar Configuración
                    </button>
                    <a href="{{ route('admin.chatbot.dashboard') }}" class="btn btn-outline-secondary btn-lg px-5 ms-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Información de Estado Actual -->
<div class="row g-4 mt-3">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Estado Actual del Sistema</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center border-end">
                        <h6 class="text-muted">Modelo Actual</h6>
                        <p class="mb-0 fw-bold">{{ $config['modelo_default'] }}</p>
                    </div>
                    <div class="col-md-3 text-center border-end">
                        <h6 class="text-muted">Temperatura</h6>
                        <p class="mb-0 fw-bold">{{ $config['temperatura_default'] }}</p>
                    </div>
                    <div class="col-md-3 text-center border-end">
                        <h6 class="text-muted">Max Tokens</h6>
                        <p class="mb-0 fw-bold">{{ $config['max_tokens_default'] }}</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h6 class="text-muted">API Configurada</h6>
                        <p class="mb-0">
                            @if($config['api_key'])
                                <span class="badge bg-success"><i class="fas fa-check"></i> Sí</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> No</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.chatbot.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Volver al Dashboard
    </a>
</div>
@endsection
