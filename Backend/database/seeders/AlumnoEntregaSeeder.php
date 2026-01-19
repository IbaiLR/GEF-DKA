<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumnoEntregaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('alumno_entrega')->insert([
            [
                'id' => 1,
                'URL_Cuaderno' => 'https://drive.local/cuadernos/ane-week1',
                'Fecha_Entrega' => '2026-01-08',
                'Observaciones' => 'Buen trabajo. Cuaderno completo y bien estructurado.',
                'ID_Alumno' => 20001,
                'ID_Entrega' => 1
            ],
            [
                'id' => 2,
                'URL_Cuaderno' => 'https://drive.local/cuadernos/unai-week1',
                'Fecha_Entrega' => '2026-01-09',
                'Observaciones' => 'Entrega correcta, aunque falta algo de detalle en las explicaciones.',
                'ID_Alumno' => 20002,
                'ID_Entrega' => 1
            ],
            [
                'id' => 3,
                'URL_Cuaderno' => 'https://drive.local/cuadernos/irati-week1',
                'Fecha_Entrega' => '2026-01-09',
                'Observaciones' => 'Muy buena presentación y contenidos bien justificados.',
                'ID_Alumno' => 20003,
                'ID_Entrega' => 1
            ],
            [
                'id' => 4,
                'URL_Cuaderno' => 'https://drive.local/cuadernos/mikel-week1',
                'Fecha_Entrega' => '2026-01-08',
                'Observaciones' => 'Trabajo incompleto. Revisar los ejercicios finales.',
                'ID_Alumno' => 20004,
                'ID_Entrega' => 2
            ],
            [
                'id' => 5,
                'URL_Cuaderno' => 'https://drive.local/cuadernos/leire-week1',
                'Fecha_Entrega' => '2026-01-09',
                'Observaciones' => 'Buen planteamiento, pero hay errores conceptuales que deben corregirse.',
                'ID_Alumno' => 20005,
                'ID_Entrega' => 2
            ],
            [
                'id' => 6,
                'URL_Cuaderno' => 'https://drive.local/cuadernos/ane-week2',
                'Fecha_Entrega' => '2026-01-13',
                'Observaciones' => 'Se aprecia mejora respecto a la entrega anterior. Sigue así.',
                'ID_Alumno' => 20001,
                'ID_Entrega' => 3
            ],
        ]);
    }
}
