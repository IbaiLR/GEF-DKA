<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function getCompanys(){
        $empresas = Empresa::all();
        return response()->json($empresas);
    }
}
