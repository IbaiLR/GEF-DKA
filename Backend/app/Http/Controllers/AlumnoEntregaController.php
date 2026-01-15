<?php

namespace App\Http\Controllers;

use App\Models\AlumnoEntrega;
use Illuminate\Http\Request;

class AlumnoEntregaController extends Controller
{
    public function entregarCuaderno(Request $request, $id)
    {
        // Si recibes archivo PDF desde un form-data
        $urlCuaderno = $request->URL_Cuaderno;

        //Para subir archivo real:
        if($request->hasFile('cuaderno')){
            $path = $request->file('cuaderno')->store('cuadernos', 'public');
            $urlCuaderno = asset('storage/'.$path);
        }

        return AlumnoEntrega::updateOrCreate(
            [
                'ID_Alumno' => $id,
                'ID_Entrega' => $request->ID_Entrega
            ],
            [
                'URL_Cuaderno' => $urlCuaderno,
                'Fecha_Entrega' => now()
            ]
        );
    }
    public function descargarCuaderno($id)
{
    $entrega = AlumnoEntrega::findOrFail($id);

    $path = str_replace(asset('storage/'), '', $entrega->URL_Cuaderno);
    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath)) {
        return response()->json(['error' => 'Archivo no encontrado'], 404);
    }

    return response()->download($fullPath);
}

}
