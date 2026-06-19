<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paes\Materia;
use App\Models\Paes\Tema;

class PaesMateriasSeeder extends Seeder
{
    public function run()
    {
        // 1. MATEMÁTICA
        $matematica = Materia::create([
            'nombre' => 'Matemática',
            'slug' => 'matematica',
            'icono' => 'calculator',
            'descripcion' => 'Prueba de Matemática PAES',
            'color' => '#3B82F6',
            'activo' => true,
            'orden' => 1,
        ]);

        $temasMatematica = [
            ['nombre' => 'Números y Álgebra', 'slug' => 'numeros-algebra', 'dificultad' => 2],
            ['nombre' => 'Geometría', 'slug' => 'geometria', 'dificultad' => 2],
            ['nombre' => 'Probabilidad y Estadística', 'slug' => 'probabilidad-estadistica', 'dificultad' => 3],
            ['nombre' => 'Funciones', 'slug' => 'funciones', 'dificultad' => 2],
            ['nombre' => 'Trigonometría', 'slug' => 'trigonometria', 'dificultad' => 3],
        ];

        foreach ($temasMatematica as $index => $tema) {
            Tema::create([
                'materia_id' => $matematica->id,
                'nombre' => $tema['nombre'],
                'slug' => $tema['slug'],
                'dificultad' => $tema['dificultad'],
                'activo' => true,
                'orden' => $index + 1,
            ]);
        }

        // 2. LENGUAJE Y COMUNICACIÓN
        $lenguaje = Materia::create([
            'nombre' => 'Lenguaje y Comunicación',
            'slug' => 'lenguaje',
            'icono' => 'book-open',
            'descripcion' => 'Prueba de Competencia Lectora PAES',
            'color' => '#10B981',
            'activo' => true,
            'orden' => 2,
        ]);

        $temasLenguaje = [
            ['nombre' => 'Comprensión Lectora', 'slug' => 'comprension-lectora', 'dificultad' => 2],
            ['nombre' => 'Vocabulario Contextual', 'slug' => 'vocabulario', 'dificultad' => 2],
            ['nombre' => 'Análisis e Interpretación', 'slug' => 'analisis-interpretacion', 'dificultad' => 3],
            ['nombre' => 'Textos Literarios', 'slug' => 'textos-literarios', 'dificultad' => 2],
            ['nombre' => 'Textos No Literarios', 'slug' => 'textos-no-literarios', 'dificultad' => 2],
        ];

        foreach ($temasLenguaje as $index => $tema) {
            Tema::create([
                'materia_id' => $lenguaje->id,
                'nombre' => $tema['nombre'],
                'slug' => $tema['slug'],
                'dificultad' => $tema['dificultad'],
                'activo' => true,
                'orden' => $index + 1,
            ]);
        }

        // 3. CIENCIAS
        $ciencias = Materia::create([
            'nombre' => 'Ciencias',
            'slug' => 'ciencias',
            'icono' => 'flask',
            'descripcion' => 'Prueba de Ciencias PAES (Biología, Química, Física)',
            'color' => '#8B5CF6',
            'activo' => true,
            'orden' => 3,
        ]);

        $temasCiencias = [
            ['nombre' => 'Biología - Célula y Herencia', 'slug' => 'biologia-celula', 'dificultad' => 2],
            ['nombre' => 'Biología - Organismos y Ecosistemas', 'slug' => 'biologia-ecosistemas', 'dificultad' => 2],
            ['nombre' => 'Química - Materia y Átomos', 'slug' => 'quimica-materia', 'dificultad' => 3],
            ['nombre' => 'Química - Reacciones Químicas', 'slug' => 'quimica-reacciones', 'dificultad' => 3],
            ['nombre' => 'Física - Mecánica', 'slug' => 'fisica-mecanica', 'dificultad' => 3],
            ['nombre' => 'Física - Energía y Ondas', 'slug' => 'fisica-energia', 'dificultad' => 3],
        ];

        foreach ($temasCiencias as $index => $tema) {
            Tema::create([
                'materia_id' => $ciencias->id,
                'nombre' => $tema['nombre'],
                'slug' => $tema['slug'],
                'dificultad' => $tema['dificultad'],
                'activo' => true,
                'orden' => $index + 1,
            ]);
        }

        // 4. HISTORIA Y CIENCIAS SOCIALES
        $historia = Materia::create([
            'nombre' => 'Historia y Ciencias Sociales',
            'slug' => 'historia',
            'icono' => 'globe',
            'descripcion' => 'Prueba de Historia y Ciencias Sociales PAES',
            'color' => '#F59E0B',
            'activo' => true,
            'orden' => 4,
        ]);

        $temasHistoria = [
            ['nombre' => 'Historia de Chile', 'slug' => 'historia-chile', 'dificultad' => 2],
            ['nombre' => 'Historia Universal', 'slug' => 'historia-universal', 'dificultad' => 2],
            ['nombre' => 'Geografía', 'slug' => 'geografia', 'dificultad' => 2],
            ['nombre' => 'Educación Cívica', 'slug' => 'educacion-civica', 'dificultad' => 2],
            ['nombre' => 'Economía', 'slug' => 'economia', 'dificultad' => 3],
        ];

        foreach ($temasHistoria as $index => $tema) {
            Tema::create([
                'materia_id' => $historia->id,
                'nombre' => $tema['nombre'],
                'slug' => $tema['slug'],
                'dificultad' => $tema['dificultad'],
                'activo' => true,
                'orden' => $index + 1,
            ]);
        }

        $this->command->info('✓ Materias y temas PAES creados exitosamente');
    }
}
