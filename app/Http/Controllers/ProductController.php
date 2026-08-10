<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    //funcion para registrar un nuevo producto
    //store es el nombre estandar cuando se quiere registrar
    public function store(AddProductRequest $request)
    {
        try {
            //trae los datos validados del form request
            $producto = Product::create($request->validated());
            return response()->json($producto, 201);
        } catch (\Exception $e) {
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
            //muestra la lista productos paginados, de 10 en 10
            $product = Product::paginate(10);
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar obtener lista de productos"], 500);
        }
    }

    //funcion buscar producto por id
    public function show(int $id)
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
    public function update(UpdateProductRequest $request, int $id)
    {
        //agregar un servico para buscar por id, y llamarlo desde aca
        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(["message", "producto no encontrado"], 404);
            }
            //trae los datos validados del form request
            $product->update($request->validated());
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar actualizar un producto"], 500);
        }
    }

    //funcion para eliminar un producto
    public function destroy(int $id)
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

    //funcion para mostrar los productos con blade
    public function catalog()
    {
        try {
            // t raelos productos paginados de 10 en 10 e incluimos su categoría
            $products = Product::with('category')->paginate(10);
            // devulve la vista Blade de productos
            return view('products.catalog', compact('products'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo cargar el catálogo de productos.');
        }
    }
}
