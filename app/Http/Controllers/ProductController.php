<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\service\ProductService;

class ProductController extends Controller
{

    //inyecta el servicio producto en el constructor
    //el constructor se ejecuta automaticamente

    public function __construct(private ProductService $productService) {}
    //funcion para registrar un nuevo producto
    //store es el nombre estandar cuando se quiere registrar
    public function store(AddProductRequest $request)
    {
        //trae los datos validados del form request
        $producto = Product::create($request->validated());
        return response()->json($producto, 201);
    }

    //funcion para mostrar todos los productos
    public function index()
    {
        //muestra la lista productos paginados, de 10 en 10
        $product = Product::paginate(10);
        return response()->json($product, 200);
    }

    //funcion buscar producto por id
    public function show(int $id)
    {
        $product = $this->productService->findById($id);
        //verifica que el producto exista
        return response()->json($product, 200);
    }

    //funcion para actualizar un producto
    public function update(UpdateProductRequest $request, int $id)
    {
        //agregar un servico para buscar por id, y llamarlo desde aca
        $product = $this->productService->findById($id);
        //trae los datos validados del form request
        $product->update($request->validated());
        return response()->json($product, 200);
    }

    //funcion para eliminar un producto
    public function destroy(int $id)
    {
        //verifica que el producto exista
        $product = $this->productService->findById($id);
        $product->delete();
        return response()->json(["message" => "producto eliminado con exito", 200]);
    }

    //funcion para mostrar los productos con blade
    public function catalog()
    {
        // t raelos productos paginados de 10 en 10 e incluimos su categoría
        $products = Product::with('category')->paginate(10);
        // devulve la vista Blade de productos
        return view('products.catalog', compact('products'));
    }
}
