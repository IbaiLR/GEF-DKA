<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotasCuadernoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notas_cuaderno')->insert([
            [
                'id' => 1,
                'ID_Alumno' => 20001,
                'Fecha' => '2026-01-10',
                'Nota' => 8.50
            ],
            [
                'id' => 2,
                'ID_Alumno' => 20002,
                'Fecha' => '2026-01-10',
                'Nota' => 7.25
            ],
            [
                'id' => 3,
                'ID_Alumno' => 20003,
                'Fecha' => '2026-01-10',
                'Nota' => 9.00
            ],
        ]);
    }
}
