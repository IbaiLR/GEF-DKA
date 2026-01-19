<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\AlumnoEntrega;
use App\Models\EntregaCuaderno;
use App\Models\NotaCuaderno;
use Illuminate\Http\Request;

class NotaCuadernoController extends Controller
{
    public function observacionesCuadernoAlumno(Request $request)
    {
        $request->validate([
            'ID_Cuaderno' => 'required|integer',
            'Observaciones' => 'required|string',
        ]);

        // Actualizar si ya existe o crear si no
        $observacion = AlumnoEntrega::updateOrCreate(
            [
                'id' => $request->ID_Cuaderno,
            ],
            [
                'Observaciones' => $request->Observaciones,
                'Fecha' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $observacion->wasRecentlyCreated ? 'Observacion creada correctamente' : 'Observacion actualizada correctamente',
            'data' => $observacion
        ]);
    }
    public function notaCuaderno(Request $request)
    {
        $request->validate([
            'ID_Alumno' => 'required|integer|exists:alumno,ID_Usuario',
            'Nota' => 'required|numeric|min:0|max:10',
        ]);

        // Actualizar o crear la nota única por alumno
        $nota = NotaCuaderno::updateOrCreate(
            ['ID_Alumno' => $request->ID_Alumno],
            [
                'Nota' => $request->Nota,
                'Fecha' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $nota->wasRecentlyCreated ? 'Nota creada correctamente' : 'Nota actualizada correctamente',
            'data' => $nota
        ]);
    }


    public function notasPorTutor($tutorId)
    {
        $alumnos = Alumno::where('ID_Tutor', $tutorId)
            ->with('usuario','notaCuaderno') // para obtener nombre, email, etc.
            ->get();

        return response()->json($alumnos);
    }
}
