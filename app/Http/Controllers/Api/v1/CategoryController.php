<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCategoryRequest;
use App\Http\Requests\CategoryIndexFormRequest;
use App\Http\Requests\FilterSearchFormRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
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
    public function store(AddCategoryRequest $request): AnonymousResourceCollection
    {
        $category = Category::create($request->validated());
        return CategoryResource::collection($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): AnonymousResourceCollection
    {
        return CategoryResource::collection($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): AnonymousResourceCollection
    {
        $category->update($request->validated());
        return CategoryResource::collection($category);
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
