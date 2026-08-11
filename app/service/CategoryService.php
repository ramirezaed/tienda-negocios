<?php

namespace App\service;

use App\Exceptions\CategoryNotFoundException;
use App\Models\Category;

class CategoryService
{
    //servicio para buscar producto por id, servicio reutilizable, para show, update, destroy
    //Produc al final asegura que devulva un objeto de tipo product
    public function findById(int $id): Category
    {
        //busca el usuario y verifica si existe
        $category = Category::find($id);
        if (!$category) {
            throw new CategoryNotFoundException("");
        }
        return $category;
    }
}
