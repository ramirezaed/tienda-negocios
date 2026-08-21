<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\Products\ProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\product\AddProductRequest;
use App\Http\Requests\product\UpdateProductRequest;
use App\Http\Requests\search\FilterSearchFormRequest;
use App\Http\Resources\product\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class PorductController extends Controller
{
    //store es el nombre estandar cuando se quiere registrar
    public function store(AddProductRequest $request): JsonResponse
    {
        // Extraemos los datos ya validados
        $data = $request->validated();
        // Instanciamos el DTO pasando
        $productDTO = ProductDTO::fromArray($data);
        //crea el nuevo producto usando los datos que vienen del dto
        $product = Product::create($productDTO->toArray());
        //
        return response()->json(new ProductResource($product), 200);
    }

    //funcion para mostrar todos los productos
    public function index(FilterSearchFormRequest $request): AnonymousResourceCollection
    {
        //se crea la consulta en el modelo product -> select *from product
        $product = Product::query()
            //cuando el parametro search no es nulo, se ejecuta la funcion
            //funcion recibe query y search
            ->when($request->query("search"), function ($query, $search) {
                //agrega la condicion where a la consulta, usa el valor search
                //devuelve query
                $query->where(function ($query) use ($search) {
                    //cuando el nombre del prodcuto sea igual que el buscado
                    $query->where("name", "like", "%{$search}%")
                        //cuando el nombre de relacion categoria es igual a search
                        ->orWhereRelation("category", "name", "like", "%{$search}%");
                });
                //si no hay parametro de busqueda devuelve todo paginados
            })->paginate(10);


        // return response()->json($product, 200);
        return ProductResource::collection($product);
    }

    //funcion buscar producto por id
    public function show(Product $product): JsonResponse
    {
        //verifica que el producto exista
        return response()->json(new ProductResource($product), 201);
    }

    //funcion para actualizar un producto
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        //trae los datos validados del form request
        $product->update($request->validated());
        return response()->json(new ProductResource($product), 200);
    }

    //funcion para eliminar un producto
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return response()->json(["message" => "producto eliminado con exito", 200]);
    }
}
