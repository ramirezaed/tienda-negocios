<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //funcion para registrar un nuevo producto
    public function register(Request $request, int $id)
    {
        $validateDate = $request->validate([
            "name" => "required|string",
            "description" => "required|string",
            "price" => "required|decimal(8,2)",
            "stock" => "required|integer",
            "category_id" => "required|integer|exists:categories,id",
        ]);
        try {
            $producto = Product::create();
            return response()->json($producto);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar registrar una nueva categoria"], 500);
        }
    }
}
