<?php

use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;


// Ruta para el listado navegable de productos con Blade
Route::get('/catalogo', [ProductController::class, 'catalog'])->name('products.catalog');
