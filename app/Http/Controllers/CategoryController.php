<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{

    //funcion para registrar una nueva categoria
    public function store(AddCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());
        return response()->json($category, 201);
    }
    //funcion para mostrar todas las categorias registradas
    public function index(): JsonResponse
    {
        //devuelve todas las categorias, paginadas de 10 en 10
        $categories = Category::paginate(10);
        return response()->json($categories, 200);
    }

    //funcion para buscar categoria por id
    public function show(Category $category)
    {
        return response()->json($category);
    }

    //funcion para modificar una categoria
    public function update(UpdateCategoryRequest $request, Category $category)
    {;
        //llma al servicio category
        $category->update($request->validated());
        return response()->json($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json(["message" => "categoria eliminada con exito"], 200);
    }
}
