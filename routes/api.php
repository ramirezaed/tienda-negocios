<?php

use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CheckoutController;

use Illuminate\Support\Facades\Route;

Route::prefix('V1')->group(function () {

    // ============ RUTAS PUBLICAS (sin autenticacion) ============

    // Products y Categories - solo index y show públicos
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

    // Auth - login y register públicos (con throttle)
    Route::post("/login", [AuthController::class, "login"])->middleware('throttle:10,1');
    Route::post("/register", [AuthController::class, "register"])->middleware('throttle:10,1');


    // ============ RUTAS PROTEGIDAS (requieren autenticación) ============

    Route::middleware(['auth:api'])->group(function () {

        // Products - métodos de escritura protegidos
        Route::apiResource('products', ProductController::class)->only(['store', 'update', 'destroy']);
        // Categories - métodos de escritura protegidos
        Route::apiResource('categories', CategoryController::class)->only(['store', 'update', 'destroy']);
        // Users - TODOS los métodos protegidos
        Route::apiResource('users', UserController::class);
        // Cart
        Route::get('cart', [CartController::class, 'index']);
        Route::post('cart/add', [CartController::class, 'addProduct']);
        Route::post('cart/clear', [CartController::class, 'clear']);
        Route::post('cart/remove', [CartController::class, 'removeProduct']);
        Route::delete('cart', [CartController::class, 'destroy']);

        // Summary
        Route::get('/summary', [CheckoutController::class, 'summary']);
        // Checkout
        Route::post('/checkout', [CheckoutController::class, 'checkout']);
        // Profile
        Route::get("/profile", [AuthController::class, "profile"])->middleware('throttle:10,1');
    });
});
