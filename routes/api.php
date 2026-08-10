<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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
    Route::post('/', [CategoryController::class, 'register']); // registrar categoria
    Route::get('/{id}', [CategoryController::class, 'getById']);    // buscar por ID
    Route::put('/{id}', [CategoryController::class, 'update']);     // modificar por ID
    Route::delete('/{id}', [CategoryController::class, 'delete']);  // eliminar por ID
});

// rutas productos
Route::prefix('products')->group(function () {
    Route::post('/', [ProductController::class, 'register']);           // registrar
    Route::get('/', [ProductController::class, 'index']);               //lista de prodyctos
    Route::get('/{id}', [ProductController::class, 'getById']);        //buscar por id
    Route::put('/{id}', [ProductController::class, 'update']);         // modificar por ID
    Route::delete('/{id}', [ProductController::class, 'delete']);       // eliminar por ID
});

// rutas cart
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);             // ver el carrito
    Route::post('/add', [CartController::class, 'addProduct']);    // Agregar  producto
    Route::post('/clear', [CartController::class, 'clear']);       // Vaciar carrito
    Route::post('/remove', [CartController::class, 'removeProduct']); // quitar un producto
    Route::delete('/delete', [CartController::class, 'deleteCart']); // eliminar carrito
});
