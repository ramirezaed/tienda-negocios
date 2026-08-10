<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //funcion para registrar una nueva categoria
    public function register(Request $request)
    {
        $validateDate = $request->validate([
            //verifica que que el nombre sea string y que no este registrado en la bd
            "name" => "required|string|unique:categories"
        ]);
        try {
            $category = Category::create($validateDate);
            return response()->json($category, 201);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar registrar una nueva categoria"], 500);
        }
    }
    //funcion para mostrar todas las categorias registradas
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar mostrar las categorias"], 500);
        }
    }
}
