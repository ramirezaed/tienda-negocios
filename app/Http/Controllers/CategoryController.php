<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
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
    public function getById(int $id)
    {
        try {
            $category = Category::find($id);
            //verifica que exista una categoria con ese id
            if (!$category) {
                return response()->json(["message" => "categoria no encontrada"], 404);
            }
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar mostrar las categorias"], 500);
        }
    }

    //funcion para modificar una categoria
    public function update(UpdateCategoryRequest $request, int $id)
    {;
        try {
            $category = Category::find($id);
            //verifica si la categoria existe
            if (!$category) {
                return response()->json(["message" => "categoria no encontrada"], 404);
            }
            $category->update($request->validated());
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar actualizar una categoria"], 500);
        }
    }

    public function delete(int $id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return response()->json(["message" => "categoria no encontrada"], 404);
            }
            $category->delete();
            return response()->json(["message" => "categoria eliminada con exito"], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar eliminar una categoria"], 500);
        }
    }
}
