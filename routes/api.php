<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\api\V1\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('V1')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource("products", PorductController::class);
});

// // rutas cart
Route::prefix('V1')->group(function () {
    Route::get('/cart/', [CartController::class, 'index']);             // ver el carrito
    Route::post('/cart/add', [CartController::class, 'addProduct']);    // Agregar  producto
    Route::post('/cart/clear', [CartController::class, 'clear']);       // Vaciar carrito
    Route::post('/cart/remove', [CartController::class, 'removeProduct']); // quitar un producto
    Route::delete('/cart/', [CartController::class, 'destroy']); // eliminar carrito
});

//rutas para resumen de carrito
Route::prefix('V1')->group(function () {
    Route::get('/summary/', [CheckoutController::class, 'summary']);             // ver resumen
});

Route::prefix('V1')->group(function () {
    Route::post('/checkout/', [CheckoutController::class, 'checkout']);    //checkout
});
