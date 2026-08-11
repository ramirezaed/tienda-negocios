<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\service\CategoryService;

class CategoryController extends Controller
{
    //inyecta el servicio de categoria en el constructor
    //el constructor se ejecuta automaticamente
    public function __construct(private CategoryService $categoryService) {}
    //funcion para registrar una nueva categoria
    public function store(AddCategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());
            return response()->json($category, 201);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar registrar una nueva categoria"], 500);
        }
    }
    //funcion para mostrar todas las categorias registradas
    public function index()
    {
        try {
            //devuelve todas las categorias, paginadas de 10 en 10
            $categories = Category::paginate(10);
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar mostrar las categorias"], 500);
        }
    }
    //funcion para buscar categoria por id
    public function show(int $id)
    {
        try {
            $category = $this->categoryService->findById($id);
            //verifica que exista una categoria con ese id
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar mostrar las categorias"], 500);
        }
    }

    //funcion para modificar una categoria
    public function update(UpdateCategoryRequest $request, int $id)
    {;
        try {
            //llma al servicio category
            $category = $this->categoryService->findById($id);
            $category->update($request->validated());
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar actualizar una categoria"], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $category = $this->categoryService->findById($id);
            $category->delete();
            return response()->json(["message" => "categoria eliminada con exito"], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar eliminar una categoria"], 500);
        }
    }
}
