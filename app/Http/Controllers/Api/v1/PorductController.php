<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class PorductController extends Controller
{
    //store es el nombre estandar cuando se quiere registrar
    public function store(AddProductRequest $request): JsonResponse
    {
        //trae los datos validados del form request
        $producto = Product::create($request->validated());
        return response()->json($producto, 201);
    }

    //funcion para mostrar todos los productos
    public function index(): JsonResponse
    {
        //muestra la lista productos paginados, de 10 en 10
        $product = Product::paginate(10);
        return response()->json($product, 200);
    }

    //funcion buscar producto por id
    public function show(Product $product): JsonResponse
    {
        //verifica que el producto exista
        return response()->json($product, 200);
    }

    //funcion para actualizar un producto
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        //trae los datos validados del form request
        $product->update($request->validated());
        return response()->json($product, 200);
    }

    //funcion para eliminar un producto
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return response()->json(["message" => "producto eliminado con exito", 200]);
    }
}
