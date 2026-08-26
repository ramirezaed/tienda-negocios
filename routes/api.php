<?php

use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CheckoutController;

use Illuminate\Support\Facades\Route;

Route::prefix('V1')->group(function () {

    // Resources
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('products', ProductController::class);

    // Cart
    Route::get('cart', [CartController::class, 'index'])->middleware("auth:api");
    Route::post('cart/add', [CartController::class, 'addProduct'])->middleware("auth:api");
    Route::post('cart/clear', [CartController::class, 'clear'])->middleware("auth:api");
    Route::post('cart/remove', [CartController::class, 'removeProduct'])->middleware("auth:api");
    Route::delete('cart', [CartController::class, 'destroy'])->middleware("auth:api");

    // Summary
    Route::get('/summary', [CheckoutController::class, 'summary'])->middleware("auth:api");

    // Checkout
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->middleware("auth:api");

    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/register", [AuthController::class, "register"]);
    Route::get("/profile", [AuthController::class, "profile"])->middleware("auth:api");
});
