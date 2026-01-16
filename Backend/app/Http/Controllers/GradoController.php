<?php

namespace App\Http\Controllers;

use App\Models\Grado;
use Illuminate\Http\Request;

class GradoController extends Controller
{
    public function getGrados(Request $request)
    {
        // Iniciamos la consulta
        $query = Grado::query();

        // Si recibimos el parámetro 'q' (búsqueda), filtramos
        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            
            // Buscamos coincidencias en Nombre O en Curso
            $query->where(function($q) use ($search) {
                $q->where('Nombre', 'LIKE', "%{$search}%")
                  ->orWhere('Curso', 'LIKE', "%{$search}%");
            });
        }

        // Paginamos el resultado (ya filtrado o completo)
        $grados = $query->paginate(5); 
        
        return response()->json($grados);
    }
}