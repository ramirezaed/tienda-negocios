<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\Categories\CategoryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCategoryRequest;
use App\Http\Requests\FilterSearchFormRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\category\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterSearchFormRequest $request): AnonymousResourceCollection
    {
        //crea la consulta en el modelo -> select *from category
        $categories = Category::query()
            //si no es nullel parametro search, se ejecuta la funcion
            ->when($request->query("search"), function ($query, $search) {
                //agrega la condicion where a la consulta
                $query->where(function ($query) use ($search) {
                    //busca la categoria con el nombre
                    $query->where("name", "like", "%{$search}%");
                });
            })->paginate(10);
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddCategoryRequest $request): JsonResponse
    {
        //extrae los datos validadso del formrequest y los asigna a data
        $data = $request->validated();
        $categoryDTO = CategoryDTO::fromArray($data);
        $category = Category::create($categoryDTO->toArray());
        return response()->json(new CategoryResource($category), 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(new CategoryResource($category), 200);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        //extrae los datos validados y lso guarda en data
        $data = $request->validated();
        //convierte los datos
        $categoriaDTO = CategoryDTO::fromArray($data);
        //actualiza usando los datos del dto
        $category->update($categoriaDTO->toArray());
        return response()->json(new CategoryResource($category), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json(["message" => "categoria eliminada con exito"], 200);
    }
}
