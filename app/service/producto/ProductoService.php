<?php

namespace App\service\producto;

use App\DTO\Products\CreateProductDTO;
use App\Models\Product;

class ProductService
{
    //devuelve un objeto producto
    public function create(CreateProductDTO $data): Product
    {
        return Product::create($data->toArray());
    }
}
