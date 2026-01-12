
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/auth', [UserController::class, 'auth'])->middleware('auth:sanctum');

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/users',[UserController::class,'getUsers']);
