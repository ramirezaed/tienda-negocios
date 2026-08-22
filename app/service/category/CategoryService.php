<?php

namespace App\service\category;

use App\DTO\Categories\CreateCategoryDTO;
use App\Models\Category;

class CategoryService
{
    //devuelve un objeto category
    public function create(CreateCategoryDTO $data): Category
    {
        return Category::create($data->toArray());
    }
}
