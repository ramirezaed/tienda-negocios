<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class ProductController extends Controller
{
    //funcion para registrar un nuevo producto
    public function register(Request $request)
    {
        $validateData = $request->validate([
            "name" => "required|string",
            "description" => "required|string",
            "price" => "required|decimal:0,2|gt:0",
            "stock" => "required|integer|gt:0",
            "category_id" => "required|integer|exists:categories,id",
        ]);
        try {
            $producto = Product::create($validateData);
            return response()->json($producto, 201);
        } catch (\Exception $e) {
            //     return response()->json(["message" => "error interno al intentar registrar un nuevo producto"], 500);
            return response()->json([
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ], 500);
        }
    }
    //funcion para mostrar todos los productos
    public function index()
    {
        try {
            $product = Product::all();
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar obtener lista de productos"], 500);
        }
    }

    //funcion buscar producto por id
    public function getById(int $id)
    {
        try {
            $product = Product::find($id);
            //verifica que el producto exista
            if (!$product) {
                return response()->json(["message" => "producto no encontrado"], 404);
            }
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar obtener datos del producto"], 500);
        }
    }
    //funcion para actualizar un producto
    public function update(Request $request, int $id)
    {
        $validateData = $request->validate([
            "name" => "sometimes|required|string",
            "description" => "sometimes|required|string",
            "price" => "sometimes|required|decimal:0,2|gt:0", //gt: 0 es para verificar que sea mayor que 0
            "stock" => "sometimes|required|integer|gt:0",
            "category_id" => "sometimes|required|integer|exists:categories,id"
        ]);
        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(["message", "producto no encontrado"], 404);
            }
            $product->update($validateData);
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar actualizar un producto"], 500);
        }
    }
    //funcion para eliminar un producto
    public function delete(int $id)
    {
        try {
            //verifica que el producto exista
            $product = Product::find($id);
            if (!$product) {
                return response()->json(["message", "producto no encontrado"], 404);
            }
            $product->delete();
            return response()->json(["message" => "producto eliminado con exito", 200]);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar eliminar un producto"], 500);
        }
    }
}
