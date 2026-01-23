<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

private function checkEsTutor($user) {
        // Solo hacemos la comprobación si el usuario es de tipo 'tutor'
        if ($user->tipo === 'tutor') {
            
            // CORRECCIÓN: Buscamos en la tabla 'grado' por la columna 'ID_Tutor'
            $existe = DB::table('grado')
                        ->where('id_tutor', $user->id) 
                        ->exists(); // Devuelve true si encuentra al menos uno
            
            // Añadimos la propiedad al objeto usuario para el frontend
            $user->es_tutor = $existe;
        } else {
            // Si no es tutor (es alumno, admin, etc), por defecto false (o lo que prefieras)
            $user->es_tutor = false;
        }
        return $user;
    }

    public function auth(Request $req)
    {
        $userAuth = $req->user();
        if (!$userAuth) {
            return response()->json([
                'status' => 'error',
                'message' => 'No autenticado'
            ], 401);
        }
    $userAuth = $this->checkEsTutor($userAuth);
        return response()->json([
            'status' => 'success',
            'message' => 'Autenticado',
            'user' => $userAuth
        ]);
    }
    public function getUser($id)
    {
        return User::find($id);
    }

    public function login(Request $req)
    {
        $credentials = $req->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Credenciales inválidas',
            ], 200);
        }

        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        $user = $this->checkEsTutor($user);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sesión cerrada correctamente'
        ], 200);
    }

    public function getUsers(Request $req)
    {
        $perPage = $req->get('per_page', 5);

        $query = User::query()->orderBy('id');

        if ($req->filled('tipo')) {
            $query->where('tipo', $req->tipo);
        }

        if ($req->filled('id_grado')) {
            $query->whereHas('alumno', function ($q) use ($req) {
                $q->where('ID_Grado', $req->id_grado);
            });
        }

        $usuarios = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $usuarios
        ]);
    }

    public function create(Request $req)
    {
        $data = $req->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'n_tel' => ['nullable', 'string', 'regex:/^[0-9]{9}$/', 'unique:users,n_tel'],
            'password' => 'required|string|min:6',
            'tipo' => 'required|string|in:alumno,tutor,instructor,admin',
            'id_grado' => 'nullable|exists:grado,id', // solo para alumno
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',
            'apellidos.max' => 'Los apellidos no pueden superar los 255 caracteres.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Debes introducir un email válido.',
            'email.unique' => 'Este email ya está registrado.',
            'n_tel.regex' => 'El número de teléfono debe tener exactamente 9 dígitos.',
            'n_tel.unique' => 'Este número de teléfono ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'tipo.required' => 'Debes especificar el tipo de usuario.',
            'tipo.in' => 'Tipo de usuario inválido.',
            'id_grado.exists' => 'El grado seleccionado no existe.',
        ]);

        // Creamos el usuario
        $user = User::create([
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'] ?? null,
            'email' => $data['email'],
            'n_tel' => $data['n_tel'] ?? null,
            'password' => $data['password'],
            'tipo' => $data['tipo'],
        ]);

        if ($req->tipo === "alumno" && isset($data['id_grado'])) {
            // Cargar la relación alumno del usuario recién creado
            $user->load('alumno');

            $alumno = $user->alumno;

            if ($alumno) {
                $alumno->ID_Grado = $data['id_grado']; // asignar el grado
                $alumno->save(); // guardar cambios
            }
        }


        return response()->json([
            'message' => "{$data['tipo']} creado correctamente",
            'usuario' => $user,
        ], 201);
    }
}
