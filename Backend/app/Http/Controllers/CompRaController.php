<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Competencia;
use App\Models\CompRa;
use Illuminate\Http\Request;

class CompRaController extends Controller
{
    public function getCompRa($gradoId)
    {
        // Competencias del grado
        $competencias = Competencia::where('ID_Grado', $gradoId)->get();

        // Asignaturas del grado con RAs y competencias
        $asignaturas = Asignatura::where('ID_Grado', $gradoId)
            ->with([
                'ras.compRas' => function ($q) {
                    $q->select('ID_Ra', 'ID_Comp');
                }
            ])
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'competencias' => $competencias,
            'asignaturas' => $asignaturas
        ]);
    }

    public function createOrDelete(Request $req)
    {
        $data = $req->validate([
            'ID_Comp' => 'required|integer',
            'ID_Ra' => 'required|integer',
            'ID_Asignatura' => 'required|integer',
        ]);

        $existing = CompRa::where('ID_Comp', $data['ID_Comp'])
            ->where('ID_Ra', $data['ID_Ra'])
            ->where('ID_Asignatura', $data['ID_Asignatura'])
            ->first();

        if ($existing) {
            $existing->delete();

            return response()->json([
                'action' => 'deleted',
                'message' => 'Relación eliminada'
            ]);
        }

        $created = CompRa::create($data);

        return response()->json([
            'action' => 'created',
            'message' => 'Relación creada',
            'data' => $created
        ]);
    }
}
