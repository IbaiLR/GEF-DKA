<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ra')->insert([
            ['id'=>1,'descripcion'=>'Documenta correctamente el trabajo realizado', 'ID_Asignatura'=>1],
            ['id'=>2,'descripcion'=>'Aplica buenas prácticas de desarrollo','ID_Asignatura'=>1],
            ['id'=>3,'descripcion'=>'Gestiona incidencias de forma autónoma', 'ID_Asignatura'=>2],
        ]);
    }
}
