<?php

namespace App\Http\Controllers\Api\V1; // <-- Tiene que ser idéntico a las carpetas en mayúscula


use App\Http\Controllers\CartController;
use App\Http\Controllers\api\V1\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// rutas de usuario
//con prefix se define el grupo de rutas
// todas estas rutas va a estar en http://127.0.0.1:8000/api/users/....
// Route::prefix('users')->group(function () {
//     Route::get('/', [UserController::class, 'index']);          // lista de usuarios
//     Route::post('/', [UserController::class, 'store']); // registrar uno nuevo
//     Route::get('/{id}', [UserController::class, 'show']);    // buscar por ID
//     Route::put('/{id}', [UserController::class, 'update']);     // modificar por ID
//     Route::delete('/{id}', [UserController::class, 'destroy']);  // eliminar por ID
// });

// rutas de categorias
//con prefix se define el grupo de rutas
//todas estas rutas va a estar en http://127.0.0.1:8000/api/categories/....
// Route::prefix('V1')->group(function () {
//     Route::get('//categories', [CategoryController::class, 'index']);          // lista de categorias
//     Route::post('/', [CategoryController::class, 'store']); // registrar categoria
//     Route::get('/{category}', [CategoryController::class, 'show']);    // buscar por ID
//     Route::put('/{category}', [CategoryController::class, 'update']);     // modificar por ID
//     Route::delete('/{category}', [CategoryController::class, 'destroy']);  // eliminar por ID
// });

Route::prefix('V1')->group(function () {
    Route::apiResource('categories', CategoryController::class);
});

// rutas productos
// Route::prefix('products')->group(function () {
//     Route::post('/', [ProductController::class, 'store']);           // registrar
//     Route::get('/', [ProductController::class, 'index']);               //lista de prodyctos
//     Route::get('/{id}', [ProductController::class, 'show']);        //buscar por id
//     Route::put('/{id}', [ProductController::class, 'update']);         // modificar por ID
//     Route::delete('/{id}', [ProductController::class, 'destroy']);       // eliminar por ID
// });

// // rutas cart
// Route::prefix('cart')->group(function () {
//     Route::get('/', [CartController::class, 'index']);             // ver el carrito
//     Route::post('/add', [CartController::class, 'addProduct']);    // Agregar  producto
//     Route::post('/clear', [CartController::class, 'clear']);       // Vaciar carrito
//     Route::post('/remove', [CartController::class, 'removeProduct']); // quitar un producto
//     Route::delete('/', [CartController::class, 'destroy']); // eliminar carrito
// });
