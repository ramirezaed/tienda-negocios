<?php

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
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/add', [CartController::class, 'addProduct']);
    Route::post('cart/clear', [CartController::class, 'clear']);
    Route::post('cart/remove', [CartController::class, 'removeProduct']);
    Route::delete('cart', [CartController::class, 'destroy']);

    // Summary
    Route::get('/summary', [CheckoutController::class, 'summary']);

    // Checkout
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
});
