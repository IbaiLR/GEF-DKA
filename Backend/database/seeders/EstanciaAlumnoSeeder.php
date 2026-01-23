<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Necesario para las fechas actuales

class EstanciaAlumnoSeeder extends Seeder
{
    public function run(): void
    {
        // Capturamos la fecha actual para los timestamps
        $now = Carbon::now();

        DB::table('estancia_alumno')->insert([
            [
                'id' => 1,
                'ID_Alumno' => 20001,
                'CIF_Empresa' => 'B12345678',
                'Fecha_inicio' => '2025-11-04',
                'Fecha_fin' => null,
                'Horas_totales' => 370, // Ejemplo de horas estándar
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'ID_Alumno' => 20002,
                'CIF_Empresa' => 'B12345678',
                'Fecha_inicio' => '2025-11-04',
                'Fecha_fin' => null,
                'Horas_totales' => 400,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'ID_Alumno' => 20003,
                'CIF_Empresa' => 'A87654321',
                'Fecha_inicio' => '2025-11-04',
                'Fecha_fin' => null,
                'Horas_totales' => null, // Ejemplo null (ya que tu migración lo permite)
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'ID_Alumno' => 20004,
                'CIF_Empresa' => 'A87654321',
                'Fecha_inicio' => '2025-11-04',
                'Fecha_fin' => null,
                'Horas_totales' => 350,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'ID_Alumno' => 20005,
                'CIF_Empresa' => 'B12345678',
                'Fecha_inicio' => '2025-11-04',
                'Fecha_fin' => null,
                'Horas_totales' => 120,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}