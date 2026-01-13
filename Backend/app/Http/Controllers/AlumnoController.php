<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function alumnosDeTutor(Request $request, int $id)
    {
        $perPage = (int) $request->query('per_page', 10);

        $alumnos = Alumno::query()
            ->where('ID_Tutor', $id)
            ->with([
                'usuario:ID,Nombre,Apellidos,Email,Tipo',
                'grado:ID,Nombre'
            ])
            ->paginate($perPage);

        
        $alumnos->getCollection()->transform(function ($a) {
            return [
                'ID_Usuario' => $a->ID_Usuario,
                'Nombre'     => $a->usuario?->Nombre,
                'Apellidos'  => $a->usuario?->Apellidos,
                'Email'      => $a->usuario?->Email,
                'Grado'      => $a->grado?->Nombre,
            ];
        });

        return response()->json([
            'data' => $alumnos
        ]);
    }

    public function alumnosDeInstructor(Request $request, int $id)
    {
        $perPage = (int) $request->query('per_page', 10);

        $alumnos = Alumno::query()
            ->where('ID_Instructor', $id)
            ->with([
                'usuario:ID,Nombre,Apellidos,Email,Tipo',
                'grado:ID,Nombre'
            ])
            ->paginate($perPage);

        $alumnos->getCollection()->transform(function ($a) {
            return [
                'ID_Usuario' => $a->ID_Usuario,
                'Nombre'     => $a->usuario?->Nombre,
                'Apellidos'  => $a->usuario?->Apellidos,
                'Email'      => $a->usuario?->Email,
                'Grado'      => $a->grado?->Nombre,
            ];
        });

        return response()->json([
            'data' => $alumnos
        ]);
    }
}
