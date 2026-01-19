<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\EntregaCuaderno;
use Illuminate\Http\Request;

class EntregaCuadernoController extends Controller
{
    public function crearEntregaCuaderno(Request $request)
    {
        return EntregaCuaderno::create([
            'Fecha_creacion' => now(),
            'Fecha_Limite' => $request->Fecha_Limite,
            'ID_Grado' => $request->ID_Grado
        ]);
    }

    public function porGrado($idGrado)
    {
        return EntregaCuaderno::with([
            'alumnoEntrega.alumno.usuario',
        ])
            ->where('ID_Grado', $idGrado)
            ->get();
    }

    public function entregasAlumno(Request $request, $id)
    {
        // Seguridad
        if ($request->user()->id != $id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Obtener grado del alumno
        $idGrado = Alumno::where('ID_Usuario', $id)->value('ID_Grado');

        if (!$idGrado) {
            return response()->json([], 200);
        }

        // Entregas del grado + entrega del alumno (si existe)
        $entregas = EntregaCuaderno::with([
            'alumnoEntrega' => function ($q) use ($id) {
                $q->where('ID_Alumno', $id);
            }
        ])
            ->where('ID_Grado', $idGrado)
            ->get();

        return response()->json($entregas);
    }

}
