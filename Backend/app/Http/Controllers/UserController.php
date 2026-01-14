<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function auth(Request $req)
    {
        $userAuth = $req->user();
        if (!$userAuth) {
            return response()->json([
                'status' => 'error',
                'message' => 'No autenticado'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Autenticado',
            'user' => $userAuth
        ]);
    }
    public function getUser($id){
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

}
