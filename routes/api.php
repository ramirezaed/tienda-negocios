<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// rutas de usuario
//con prefix se define el grupo de rutas
// todas estas rutas va a estar en http://127.0.0.1:8000/api/users/....
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);          // lista de usuarios
    Route::post('/register', [UserController::class, 'register']); // registrar uno nuevo
    Route::get('/{id}', [UserController::class, 'getById']);    // buscar por ID
    Route::put('/{id}', [UserController::class, 'update']);     // modificar por ID
    Route::delete('/{id}', [UserController::class, 'delete']);  // eliminar por ID
});

// rutas de categorias
//con prefix se define el grupo de rutas
//todas estas rutas va a estar en http://127.0.0.1:8000/api/categories/....
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);          // lista de categorias
    Route::post('/register', [CategoryController::class, 'register']); // registrar categoria
    Route::get('/{id}', [CategoryController::class, 'getById']);    // buscar por ID
    Route::put('/{id}', [CategoryController::class, 'update']);     // modificar por ID
    Route::delete('/{id}', [CategoryController::class, 'delete']);  // eliminar por ID
});
