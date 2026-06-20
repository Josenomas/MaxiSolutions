<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paes\Materia;
use App\Models\Paes\Tema;
use App\Models\Paes\Pregunta;
use App\Models\Paes\Alternativa;

class PaesPreguntasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $matematica = Materia::where('nombre', 'Matemática')->first();
        $lenguaje = Materia::where('nombre', 'Lenguaje y Comunicación')->first();

        if (!$matematica || !$lenguaje) {
            $this->command->error('❌ Ejecuta primero PaesMateriasSeeder');
            return;
        }

        $algebra = Tema::where('materia_id', $matematica->id)->where('slug', 'numeros-algebra')->first();
        $geometria = Tema::where('materia_id', $matematica->id)->where('slug', 'geometria')->first();
        $comprension = Tema::where('materia_id', $lenguaje->id)->first();

        $this->seedPreguntas($matematica, $lenguaje, $algebra, $geometria, $comprension);
        
        $this->command->info('✅ 12 preguntas PAES creadas');
    }

    private function seedPreguntas($mat, $len, $alg, $geo, $comp)
    {
        // ÁLGEBRA
        $preguntas_alg = [
            ['¿Cuál es x en 3x + 7 = 22?', 'facil', '3x=15, x=5', [['A','3',0],['B','5',1],['C','7',0],['D','15',0]]],
            ['Factorizar x² - 9:', 'medio', '(x+3)(x-3)', [['A','(x-9)(x+1)',0],['B','(x+3)(x-3)',1],['C','(x-3)²',0],['D','x(x-9)',0]]],
            ['En 2x+y=10, x-y=2, x=?', 'medio', '3x=12, x=4', [['A','2',0],['B','3',0],['C','4',1],['D','6',0]]],
            ['Raíces de x²-5x+6=0:', 'medio', '(x-2)(x-3)=0', [['A','1,6',0],['B','2,3',1],['C','-2,-3',0],['D','5,6',0]]],
            ['25% de 80:', 'facil', '0.25×80=20', [['A','15',0],['B','20',1],['C','25',0],['D','30',0]]]
        ];
        foreach ($preguntas_alg as $p) {
            $pregunta = Pregunta::create(['materia_id'=>$mat->id,'tema_id'=>$alg->id,'enunciado'=>$p[0],'dificultad'=>$p[1],'explicacion'=>$p[2],'generada_ia'=>false]);
            foreach ($p[3] as $alt) Alternativa::create(['pregunta_id'=>$pregunta->id,'letra'=>$alt[0],'texto'=>$alt[1],'es_correcta'=>$alt[2]]);
        }
        
        // GEOMETRÍA
        $preguntas_geo = [
            ['Perímetro cuadrado lado 5cm:', 'facil', '4×5=20', [['A','10cm',0],['B','15cm',0],['C','20cm',1],['D','25cm',0]]],
            ['Área círculo r=3 (π=3.14):', 'medio', 'π×9=28.26', [['A','9.42',0],['B','18.84',0],['C','28.26',1],['D','37.68',0]]],
            ['Triángulo 3,4,h:', 'medio', 'Pitágoras h=5', [['A','4cm',0],['B','5cm',1],['C','6cm',0],['D','7cm',0]]]
        ];
        foreach ($preguntas_geo as $p) {
            $pregunta = Pregunta::create(['materia_id'=>$mat->id,'tema_id'=>$geo->id,'enunciado'=>$p[0],'dificultad'=>$p[1],'explicacion'=>$p[2],'generada_ia'=>false]);
            foreach ($p[3] as $alt) Alternativa::create(['pregunta_id'=>$pregunta->id,'letra'=>$alt[0],'texto'=>$alt[1],'es_correcta'=>$alt[2]]);
        }
        
        // LENGUAJE
        $preguntas_len = [
            ['Cambio climático NO afecta:', 'facil', 'No menciona sequías', [['A','Temperatura',0],['B','Glaciares',0],['C','Sequías',1],['D','Eventos',0]]],
            ['María truenos paraguas:', 'medio', 'Va a llover', [['A','Miedo',0],['B','Lluvia',1],['C','Olvidó',0],['D','Rota',0]]],
            ['INEQUÍVOCA significa:', 'medio', 'Clara', [['A','Injusta',0],['B','Dudosa',0],['C','Clara',1],['D','Rápida',0]]],
            ['IA idea principal:', 'medio', 'Beneficios+desafíos', [['A','Medicina',0],['B','Ambos',1],['C','Peligro',0],['D','Transporte',0]]]
        ];
        foreach ($preguntas_len as $p) {
            $pregunta = Pregunta::create(['materia_id'=>$len->id,'tema_id'=>$comp->id,'enunciado'=>$p[0],'dificultad'=>$p[1],'explicacion'=>$p[2],'generada_ia'=>false]);
            foreach ($p[3] as $alt) Alternativa::create(['pregunta_id'=>$pregunta->id,'letra'=>$alt[0],'texto'=>$alt[1],'es_correcta'=>$alt[2]]);
        }
        
        $this->command->info('  → 5 Álgebra + 3 Geometría + 4 Lenguaje');
    }
}
