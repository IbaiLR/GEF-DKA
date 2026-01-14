<?php

namespace App\Http\Controllers;

use App\Models\EstanciaAlumno;
use Illuminate\Http\Request;

class EstanciaController extends Controller
{
    public function getEstancias(){
        $estancias = EstanciaAlumno::all();
        return response()->json($estancias);
    }

    public function getEstanciaAlumno($id){
        $estancia=EstanciaAlumno::find($id);
        return response()->json($estancia);
    }
}
