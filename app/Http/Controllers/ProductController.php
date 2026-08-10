<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

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
            return response()->json($producto, 201);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar registrar un nuevo producto"], 500);
        }
    }

    public function index()
    {
        try {
            $product = Product::all();
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar obtener lista de productos"], 500);
        }
    }
    public function getById(int $id)
    {
        try {
            $product = Product::find($id);
            if ($product) {
                return response()->json(["message" => "producto no encontrado"], 404);
            }
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar obtener datos del producto"], 500);
        }
    }
}
