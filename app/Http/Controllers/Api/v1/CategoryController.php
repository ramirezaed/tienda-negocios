<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\Categories\CreateCategoryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\category\AddCategoryRequest;
use App\Http\Requests\category\UpdateCategoryRequest;
use App\Http\Requests\search\FilterSearchFormRequest;
use App\Http\Resources\category\CategoryResource;
use App\Models\Category;
use App\service\category\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(private CategoryService $categoryService) {}

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

        $category = $this->categoryService->create($request->toDTO());
        return response()->json(new CategoryResource($category), 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(new CategoryResource($category), 200);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {

        $category = $this->categoryService->update($request->toDTO(), $category);
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
