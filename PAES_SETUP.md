# Configuración Sistema PAES - MaxiSolutions

## Variables de entorno necesarias (.env)

Agregar al archivo `.env`:

```bash
# Claude AI API (para generación de preguntas y tutor IA)
CLAUDE_API_KEY=your-claude-api-key-here
CLAUDE_MODEL=claude-3-5-sonnet-20241022
CLAUDE_MAX_TOKENS=4096
```

## Obtener API Key de Claude

1. Visita: https://console.anthropic.com/
2. Crea una cuenta o inicia sesión
3. Ve a "API Keys"
4. Genera una nueva API key
5. Copia la key y agrégala al .env

## Costo estimado

- **Claude 3.5 Sonnet**: ~$3 USD por millón de tokens
- **Estimado por pregunta generada**: ~500-1000 tokens = $0.0015 - $0.003 USD
- **100 preguntas generadas**: ~$0.15 - $0.30 USD

## Instalación

1. Agregar configuración a `config/services.php`:

```php
'claude' => [
    'api_key' => env('CLAUDE_API_KEY'),
    'model' => env('CLAUDE_MODEL', 'claude-3-5-sonnet-20241022'),
    'max_tokens' => env('CLAUDE_MAX_TOKENS', 4096),
],
```

2. Ejecutar migraciones:

```bash
php artisan migrate
```

3. Ejecutar seeders:

```bash
php artisan db:seed --class=PaesSeeder
```

## Arquitectura Multi-Tenant

### Tablas principales:

- `organizaciones` - Empresas/instituciones que usan el sistema
- `productos` - Productos SaaS disponibles (PAES, futuros ERP, CRM, etc.)
- `planes` - Planes de suscripción por producto
- `suscripciones` - Relación organización-producto-plan
- `uso_producto` - Métricas de uso para billing y límites

### Tablas PAES:

- `paes_materias` - Matemática, Lenguaje, Ciencias, Historia
- `paes_temas` - Temas por materia
- `paes_preguntas` - Banco de preguntas (generadas por IA o manuales)
- `paes_alternativas` - Opciones de cada pregunta
- `paes_sesiones` - Sesiones de práctica de usuarios
- `paes_respuestas` - Respuestas de usuarios
- `paes_analisis` - Análisis de desempeño generado por IA
- `paes_chat_ia` - Interacciones con tutor virtual
- `paes_progreso` - Progreso por materia/tema
- `paes_metas` - Metas de estudio

## Uso del servicio de IA

```php
use App\Services\ClaudeAIService;

$claude = new ClaudeAIService();

// Generar una pregunta
$pregunta = $claude->generarPregunta('Matemática', 'Álgebra', 'medio');

// Explicar error
$explicacion = $claude->explicarError(
    $pregunta,
    'Respuesta del estudiante',
    'Respuesta correcta',
    'Contexto adicional'
);

// Analizar desempeño
$analisis = $claude->analizarDesempeno($estadisticas, 'Matemática', 'Álgebra');

// Responder duda
$respuesta = $claude->responderDuda('¿Cómo resuelvo ecuaciones cuadráticas?');
```

## Límites por plan (ejemplo)

### Plan Gratuito
- 10 preguntas IA por día
- Sin tutor virtual
- Estadísticas básicas

### Plan Básico ($5.990/mes)
- 100 preguntas IA por día
- 20 consultas tutor IA por día
- Simuladores ilimitados
- Estadísticas avanzadas

### Plan Premium ($12.990/mes)
- Preguntas IA ilimitadas
- Tutor IA ilimitado
- Análisis personalizado semanal
- Predicción de puntaje PAES
- Acceso API

## Próximos pasos

1. ✅ Crear migraciones multi-tenant
2. ✅ Crear modelos con TenantScope
3. ✅ Implementar ClaudeAIService
4. ⏳ Crear seeders con datos iniciales
5. ⏳ Crear controladores PAES
6. ⏳ Crear vistas de práctica
7. ⏳ Implementar dashboard estudiante
8. ⏳ Crear marketplace en MaxiSolutions

## Estructura de URLs

```
maxisolutions.cl              → Sitio principal
maxisolutions.cl/login        → Login compartido (SSO)
maxisolutions.cl/productos    → Marketplace de productos
maxisolutions.cl/paes         → Acceso a PAES
maxisolutions.cl/paes/dashboard
maxisolutions.cl/paes/practicar
maxisolutions.cl/paes/simulador
maxisolutions.cl/paes/progreso
maxisolutions.cl/paes/tutor
```

## Notas importantes

- Cada pregunta generada consume ~500-1000 tokens de Claude API
- El sistema registra automáticamente el uso en `uso_producto`
- Los scopes multi-tenant se aplican automáticamente mediante `TenantScope` trait
- Los usuarios solo ven datos de su organización
- Los admins pueden ver datos de todas las organizaciones usando `allTenants()`
