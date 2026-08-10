<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Ruta para el listado navegable de productos con Blade
Route::get('/catalogo', [ProductController::class, 'catalog'])->name('products.catalog');
