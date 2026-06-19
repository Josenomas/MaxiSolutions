<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Materias PAES
        Schema::create('paes_materias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Matemática, Lenguaje, Ciencias, Historia
            $table->string('slug')->unique();
            $table->string('icono')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('color')->nullable(); // Para UI
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // 2. Temas por materia
        Schema::create('paes_temas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')->constrained('paes_materias')->onDelete('cascade');
            $table->string('nombre'); // Álgebra, Geometría, Comprensión lectora, etc.
            $table->string('slug');
            $table->text('descripcion')->nullable();
            $table->integer('dificultad')->default(2); // 1=fácil, 2=medio, 3=difícil
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();

            $table->index(['materia_id', 'activo']);
        });

        // 3. Preguntas
        Schema::create('paes_preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacion_id')->nullable()->constrained('organizaciones')->onDelete('cascade');
            $table->foreignId('tema_id')->constrained('paes_temas')->onDelete('cascade');
            $table->text('enunciado');
            $table->text('contexto')->nullable(); // Texto de apoyo, gráficos, tablas
            $table->string('tipo')->default('seleccion_multiple'); // seleccion_multiple, verdadero_falso
            $table->integer('dificultad')->default(2);
            $table->string('fuente')->default('ia'); // ia, manual, oficial
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->text('explicacion')->nullable(); // Explicación de la respuesta correcta
            $table->json('metadata')->nullable(); // Tags, año PAES, etc.
            $table->boolean('verificada')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tema_id', 'dificultad', 'activo']);
        });

        // 4. Alternativas de preguntas
        Schema::create('paes_alternativas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregunta_id')->constrained('paes_preguntas')->onDelete('cascade');
            $table->char('letra', 1); // A, B, C, D, E
            $table->text('texto');
            $table->boolean('es_correcta')->default(false);
            $table->text('explicacion')->nullable(); // Por qué es incorrecta
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // 5. Sesiones de práctica
        Schema::create('paes_sesiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacion_id')->constrained('organizaciones')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('materia_id')->nullable()->constrained('paes_materias')->onDelete('set null');
            $table->string('tipo')->default('practica'); // practica, simulador, desafio
            $table->string('modo')->default('normal'); // normal, examen, adaptativo
            $table->integer('total_preguntas')->default(0);
            $table->integer('correctas')->default(0);
            $table->integer('incorrectas')->default(0);
            $table->integer('omitidas')->default(0);
            $table->decimal('porcentaje', 5, 2)->default(0);
            $table->integer('tiempo_total_segundos')->nullable();
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_fin')->nullable();
            $table->string('estado')->default('en_progreso'); // en_progreso, completada, abandonada
            $table->json('configuracion')->nullable(); // Tiempo límite, # preguntas, etc.
            $table->timestamps();

            $table->index(['user_id', 'estado', 'created_at']);
        });

        // 6. Respuestas del usuario
        Schema::create('paes_respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesion_id')->constrained('paes_sesiones')->onDelete('cascade');
            $table->foreignId('pregunta_id')->constrained('paes_preguntas')->onDelete('cascade');
            $table->foreignId('alternativa_id')->nullable()->constrained('paes_alternativas')->onDelete('set null');
            $table->boolean('es_correcta')->default(false);
            $table->boolean('omitida')->default(false);
            $table->integer('tiempo_segundos')->nullable();
            $table->integer('numero_pregunta'); // Orden en la sesión
            $table->timestamps();

            $table->index(['sesion_id', 'pregunta_id']);
        });

        // 7. Análisis de desempeño por IA
        Schema::create('paes_analisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('materia_id')->nullable()->constrained('paes_materias')->onDelete('set null');
            $table->foreignId('tema_id')->nullable()->constrained('paes_temas')->onDelete('set null');
            $table->decimal('nivel_dominio', 5, 2)->default(0); // 0-100
            $table->text('fortalezas')->nullable(); // Generado por IA
            $table->text('debilidades')->nullable(); // Generado por IA
            $table->text('recomendaciones')->nullable(); // Generado por IA
            $table->json('estadisticas')->nullable(); // Datos detallados
            $table->date('fecha_analisis');
            $table->timestamps();

            $table->index(['user_id', 'materia_id', 'fecha_analisis']);
        });

        // 8. Interacciones con tutor IA
        Schema::create('paes_chat_ia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pregunta_id')->nullable()->constrained('paes_preguntas')->onDelete('set null');
            $table->foreignId('tema_id')->nullable()->constrained('paes_temas')->onDelete('set null');
            $table->text('pregunta_usuario');
            $table->text('respuesta_ia');
            $table->string('tipo')->default('explicacion'); // explicacion, duda, concepto
            $table->integer('tokens_usados')->default(0);
            $table->boolean('util')->nullable(); // Feedback del usuario
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        // 9. Progreso del estudiante
        Schema::create('paes_progreso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('materia_id')->constrained('paes_materias')->onDelete('cascade');
            $table->foreignId('tema_id')->nullable()->constrained('paes_temas')->onDelete('set null');
            $table->integer('preguntas_realizadas')->default(0);
            $table->integer('preguntas_correctas')->default(0);
            $table->decimal('porcentaje_acierto', 5, 2)->default(0);
            $table->integer('tiempo_promedio_segundos')->default(0);
            $table->integer('racha_actual')->default(0); // Días consecutivos
            $table->integer('racha_maxima')->default(0);
            $table->date('ultima_practica')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'materia_id', 'tema_id']);
        });

        // 10. Metas del estudiante
        Schema::create('paes_metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('tipo')->default('puntaje'); // puntaje, preguntas_dia, tiempo_estudio
            $table->integer('objetivo'); // 750 puntos, 50 preguntas/día, 2h/día
            $table->integer('progreso_actual')->default(0);
            $table->date('fecha_inicio');
            $table->date('fecha_limite')->nullable();
            $table->string('estado')->default('activa'); // activa, completada, abandonada
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paes_metas');
        Schema::dropIfExists('paes_progreso');
        Schema::dropIfExists('paes_chat_ia');
        Schema::dropIfExists('paes_analisis');
        Schema::dropIfExists('paes_respuestas');
        Schema::dropIfExists('paes_sesiones');
        Schema::dropIfExists('paes_alternativas');
        Schema::dropIfExists('paes_preguntas');
        Schema::dropIfExists('paes_temas');
        Schema::dropIfExists('paes_materias');
    }
};
