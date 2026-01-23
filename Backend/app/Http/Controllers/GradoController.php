<?php

namespace App\Http\Controllers;

use App\Http\Services\NotasAlumnoService;
use App\Models\Grado;
use App\Models\Asignatura;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradoController extends Controller
{
protected $notasService;

    public function __construct(NotasAlumnoService $notasService)
    {
        $this->notasService = $notasService;
    }



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
    // Obtener asignaturas simples
    public function getAsignaturas($id)
    {
        // Buscamos las asignaturas que pertenezcan a este grado
        // Asegúrate de usar 'asignaturas' si es así en tu modelo
        $asignaturas = Asignatura::where('ID_Grado', $id)->orderBy('id')->get();
        return response()->json($asignaturas);
    }

    // Obtener competencias (Grado -> Asignatura -> Ra -> CompRa -> Competencia)
    public function getCompetencias($id)
    {
        $grado = Grado::find($id);

        if (!$grado) {
            return response()->json([], 404);
        }

        $competencias = $grado->competencias()->orderBy('id')->get();

        return response()->json($competencias);
    }

   public function getDatosGestionTutor(Request $request)
    {
        $user = $request->user();

        // 1. Obtener Grado
        $grado = DB::table('grado')->where('ID_Tutor', $user->id)->first();
        if (!$grado) return response()->json(['message' => 'Sin grado'], 404);

        // 2. Obtener Asignaturas
        $asignaturas = DB::table('asignatura')->where('ID_Grado', $grado->id)->get();

        // 3. Obtener Alumnos
        $alumnos = User::where('tipo', 'alumno')
            ->whereHas('alumno', function($q) use ($grado) {
                $q->where('ID_Grado', $grado->id);
            })
            ->with('alumno') // Cargar relación alumno para tener su ID
            ->orderBy('apellidos', 'asc')
            ->get();

        // 4. --- MAGIA AQUÍ: Inyectar notas a cada alumno ---
        // Transformamos la colección de alumnos para añadirle las notas
        $alumnos = $alumnos->map(function ($usuarioAlumno) use ($grado) {
            // Obtenemos el ID de la tabla 'alumno' (no del user)
            $idAlumnoTabla = $usuarioAlumno->alumno->id;

            // Llamamos al servicio
            $notas = $this->notasService->obtenerNotasDelAlumno($idAlumnoTabla, $grado->id);

            // Añadimos la propiedad al objeto JSON
            $usuarioAlumno->notas_calculadas = $notas;

            return $usuarioAlumno;
        });

        return response()->json([
            'grado' => $grado,
            'alumnos' => $alumnos,
            'asignaturas' => $asignaturas
        ]);
    }
}
