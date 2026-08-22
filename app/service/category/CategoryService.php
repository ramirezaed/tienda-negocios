<?php

namespace App\service\category;

use App\DTO\Categories\CreateCategoryDTO;
use App\DTO\Categories\UpdateCategoryDTO;
use App\Models\Category;
use App\Models\Product;

class CategoryService
{
    //devuelve un objeto category
    public function create(CreateCategoryDTO $data): Category
    {
        return Category::create($data->toArray());
    }

    public function update(UpdateCategoryDTO $data, Category $category): Category
    {
        //si no hay cambios en la category
        //hasChange comprueba si hay cambios
        if (!$data->hasChanges()) {
            return $category;
        }
        $category->update($data->toArray());
        return $category;
    }
}
