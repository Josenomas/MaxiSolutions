# 🎓 PAES Prep - Sistema de Preparación PAES

Sistema completo de preparación para la Prueba de Acceso a la Educación Superior (PAES) de Chile.

## ✨ Funcionalidades

✅ **Sistema de Práctica Interactiva**
- Preguntas por materia y tema
- Timer en tiempo real
- Feedback visual inmediato (verde/rojo)
- Explicaciones detalladas
- Progress tracker animado

✅ **Sistema de Planes**
- Gratuito: 10 preguntas/día
- Básico: 100 preguntas/día + IA
- Premium: Ilimitado + Tutor Virtual
- Institucional: Planes personalizados

✅ **Dashboard y Estadísticas**
- Progreso por materia
- Racha de estudio
- Análisis de rendimiento
- Historial de sesiones

## 🚀 Instalación Rápida

### 1. Ejecutar Migraciones

```bash
php artisan migrate
```

### 2. Poblar Base de Datos

```bash
# Crear materias y temas
php artisan db:seed --class=PaesMateriasSeeder

# Crear preguntas de ejemplo (12 preguntas)
php artisan db:seed --class=PaesPreguntasSeeder
```

### 3. Acceder al Sistema

**Producción:**
```
Landing: https://paes.maxisolutions.cl
Dashboard: https://paes.maxisolutions.cl/app
```

**Local (modificar hosts):**
```
# Windows: C:\Windows\System32\drivers\etc\hosts
127.0.0.1 paes.maxisolutions.test
```

## 🏗️ Arquitectura

### Multi-Tenant SaaS

```
maxisolutions.cl          → Admin principal
paes.maxisolutions.cl     → Landing PAES
paes.maxisolutions.cl/app → Dashboard (auth)
```

### Guards

- `web` → Admin MaxiSolutions
- `paes` → Estudiantes PAES

### Base de Datos

**14 tablas PAES:**
- `paes_users` - Estudiantes
- `paes_materias` - 4 materias
- `paes_temas` - 21 temas
- `paes_preguntas` - Banco de preguntas
- `paes_alternativas` - Opciones A,B,C,D
- `paes_sesiones` - Sesiones de práctica
- `paes_respuestas` - Respuestas
- `paes_progresos` - Progreso por materia
- Y más...

## 📁 Estructura

```
app/
├── Http/Controllers/Paes/
│   ├── PaesController.php      # Dashboard, stats
│   └── PreguntaController.php  # API práctica
├── Models/Paes/
│   ├── Materia.php
│   ├── Pregunta.php
│   ├── Sesion.php
│   └── ...

database/
├── migrations/
│   └── *_create_paes_*.php     # 14 migraciones
└── seeders/
    ├── PaesMateriasSeeder.php  # 4 materias, 21 temas
    └── PaesPreguntasSeeder.php # 12 preguntas ejemplo

resources/views/paes/
├── landing.blade.php           # Landing pública
├── dashboard.blade.php         # Dashboard
└── practica.blade.php          # ⭐ Sistema práctica

routes/
└── web.php                     # Rutas con subdominio
```

## 🎯 API Endpoints

### Práctica

```http
POST /app/api/preguntas/iniciar
Content-Type: application/json
{
  "materia_id": 1,
  "tema_id": 2,
  "cantidad": 10
}
```

```http
POST /app/api/preguntas/responder
{
  "sesion_id": 123,
  "pregunta_id": 456,
  "alternativa_id": 789,
  "tiempo": 45
}
```

```http
POST /app/api/sesion/{id}/finalizar
```

```http
GET /app/api/materias/{id}/temas
```

## 💾 Modelos Principales

```php
// Relaciones
Materia hasMany Temas
Materia hasMany Preguntas
Pregunta belongsTo Materia
Pregunta belongsTo Tema
Pregunta hasMany Alternativas
Sesion hasMany Respuestas
```

## 🎨 Vista de Práctica

**3 Secciones:**

1. **Configuración**
   - Selector de materia
   - Selector de tema (AJAX)
   - Cantidad de preguntas

2. **Práctica**
   - Timer MM:SS
   - Progress dots
   - Alternativas clickeables
   - Feedback inmediato
   - Explicaciones

3. **Resultados**
   - Total preguntas
   - Correctas/Incorrectas
   - Porcentaje acierto

## 📊 Sistema de Planes

| Plan | Precio | Preguntas/día | IA | Tutor |
|------|--------|---------------|-----|-------|
| Gratuito | $0 | 10 | ❌ | ❌ |
| Básico | $5.990 | 100 | ✅ | ❌ |
| Premium | $12.990 | ∞ | ✅ | ✅ |
| Institucional | Custom | ∞ | ✅ | ✅ |

**Validación de límites en:**
`PreguntaController::verificarLimitePreguntas()`

## 🧪 Testing

```bash
# Verificar materias
php artisan tinker
>>> App\Models\Paes\Materia::count(); // 4

# Verificar preguntas
>>> App\Models\Paes\Pregunta::count(); // 12

# Ver última sesión
>>> App\Models\Paes\Sesion::with('respuestas')->latest()->first();

# Limpiar sesiones de prueba
>>> App\Models\Paes\Sesion::truncate();
>>> App\Models\Paes\Respuesta::truncate();
```

## 📝 Preguntas de Ejemplo

**Álgebra (5):**
- Ecuaciones lineales
- Factorización
- Sistemas de ecuaciones
- Ecuaciones cuadráticas
- Porcentajes

**Geometría (3):**
- Perímetro
- Área de círculo
- Teorema de Pitágoras

**Lenguaje (4):**
- Comprensión literal
- Inferencia
- Vocabulario
- Idea principal

## 🔧 Configuración DNS

```
Tipo: A
Nombre: paes
IPv4: 54.165.20.122
Proxy: ✅ Activado
```

## 🐛 Troubleshooting

**MySQL no conecta:**
```bash
sudo systemctl start mysql
```

**Tabla no existe:**
```bash
php artisan migrate:status
php artisan migrate
```

**Temas no cargan:**
- Verificar `practica.blade.php` línea 207
- Verificar ruta API en `routes/web.php`

**Subdominio no funciona local:**
- Modificar `C:\Windows\System32\drivers\etc\hosts`
- O comentar `Route::domain()` en routes

## 📈 Próximos Pasos

- [ ] Autenticación PAES (Login/Register)
- [ ] Generación preguntas con Claude AI
- [ ] Tutor virtual ChatGPT
- [ ] Simulador cronometrado completo
- [ ] Integración Flow/Transbank
- [ ] Banco 1000+ preguntas
- [ ] Analytics y gráficos
- [ ] App móvil

## 📦 Commits Importantes

```
e00483b - Implementar PreguntaController y API práctica
9fcdf8f - Implementar vista completa de práctica interactiva
b2cab29 - Agregar seeder con 12 preguntas de ejemplo
```

## 👨‍💻 Autor

**José Norambuena**  
MaxiSolutions - Soluciones Tecnológicas

## 📄 Licencia

Propietario © 2024-2026 MaxiSolutions
