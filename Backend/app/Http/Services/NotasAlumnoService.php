<?php

namespace App\Http\Services;

use App\Models\Asignatura;
use App\Models\EstanciaAlumno; // O como llames a la tabla de estancia
use App\Models\Ra;
use Illuminate\Support\Facades\DB;

class NotasAlumnoService
{
    /**
     * Devuelve un array con todas las notas calculadas para un alumno
     */
    public function obtenerNotasDelAlumno($idAlumno, $idGrado)
    {
        // 1. Buscar la estancia activa del alumno
        // Ajusta esto según tu lógica de estancias (ej: la del año actual)
        $estancia = EstanciaAlumno::where('ID_Alumno', $idAlumno)->first();

        if (!$estancia) {
            return []; // Si no tiene estancia, no tiene notas de empresa
        }

        // --- A. NOTAS COMUNES (Transversal y Cuaderno) ---

        // 1. Nota Cuaderno (Suponiendo que se guarda en la tabla estancia o una tabla notas_estancia)
        $notaCuaderno = $estancia->nota_cuaderno ?? 0; // Valor 0-10

        // 2. Nota Transversal (Calculada de las competencias transversales evaluadas 1-4)
        $notaTransversal = $this->calcularMediaTransversal($estancia->id);

        // --- B. NOTAS POR ASIGNATURA ---

        $asignaturas = Asignatura::where('ID_Grado', $idGrado)->get();
        $notasPorAsignatura = [];

        foreach ($asignaturas as $asig) {

            // 1. Nota Técnica (Calculada compleja RA -> Competencias)
            $notaTecnica = $this->calcularNotaTecnica($asig->id, $estancia->id, $notaTransversal);

            // 2. Nota Egibide (Directa de BD, puesta por el tutor)
            // Suponemos una tabla 'calificaciones_centro' o similar
            $notaEgibide = DB::table('nota_asignatura') // <--- CAMBIA ESTO POR TU TABLA REAL
                ->where('ID_Alumno', $idAlumno)
                ->where('ID_Asignatura', $asig->id)
                ->value('nota_egibide') ?? null; // Null si aún no se ha puesto

            $notasPorAsignatura[$asig->id] = [
                'tecnica'     => round($notaTecnica, 2),
                'transversal' => round($notaTransversal, 2), // Es la misma para todas
                'cuaderno'    => round($notaCuaderno, 2),    // Es la misma para todas
                'egibide'     => $notaEgibide !== null ? round($notaEgibide, 2) : '-',

                // Cálculo provisional de la final (si tienes los datos)
                // (Tecnica*0.6 + Trans*0.2 + Cuaderno*0.2)*0.2 + Egibide*0.8
                'final'       => '-' // Lo dejaremos para el siguiente paso si quieres
            ];
        }

        return $notasPorAsignatura;
    }

    // --- FUNCIONES AUXILIARES ---

    private function calcularMediaTransversal($idEstancia)
    {
        // Buscamos competencias transversales evaluadas en esta estancia
        // Suponemos tabla 'evaluacion_competencia' con ID_Estancia, ID_Competencia, Nota (1-4)
        // Y que las competencias transversales tienen un flag o IDs específicos.
        // AQUI ASUMO que en tu tabla competencia hay un campo 'tipo'='transversal'

        $medias = DB::table('evaluacion_competencia')
            ->join('competencia', 'evaluacion_competencia.ID_Competencia', '=', 'competencia.id')
            ->where('evaluacion_competencia.ID_Estancia', $idEstancia)
            ->where('competencia.tipo', 'transversal') // <--- AJUSTA ESTO
            ->avg('evaluacion_competencia.nota'); // Media 1-4

        // Si no hay notas, devolvemos 0
        if (!$medias) return 0;

        // Convertimos escala 1-4 a 0-10
        return $medias * 2.5;
    }

    private function calcularNotaTecnica($idAsignatura, $idEstancia, $notaTransversalFallback)
    {
        // 1. Obtener RAs de la asignatura
        $ras = Ra::where('ID_Asignatura', $idAsignatura)->get();

        $notasDeRAs = [];

        foreach ($ras as $ra) {
            // Obtener competencias técnicas vinculadas a este RA (Tabla pivote comp_ra)
            $competenciasIds = DB::table('comp_ra')
                ->where('ID_Ra', $ra->id)
                ->pluck('ID_Comp');

            // Obtener las notas que el instructor ha puesto a esas competencias
            $notas = DB::table('evaluacion_competencia')
                ->where('ID_Estancia', $idEstancia)
                ->whereIn('ID_Competencia', $competenciasIds)
                ->pluck('nota'); // Colección de notas 1-4

            if ($notas->count() > 0) {
                // Media de las competencias de ESTE RA
                $notasDeRAs[] = $notas->avg();
            }
        }

        // 2. Calcular media del módulo
        if (count($notasDeRAs) > 0) {
            $mediaModulo4 = collect($notasDeRAs)->avg(); // Media sobre 4
            return $mediaModulo4 * 2.5; // Conversión a sobre 10
        } else {
            // Si no hay competencias técnicas evaluadas para esta asignatura,
            // HEREDA la nota transversal
            return $notaTransversalFallback;
        }
    }
}
