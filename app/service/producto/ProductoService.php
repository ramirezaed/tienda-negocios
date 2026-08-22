<?php

namespace App\service\producto;

use App\DTO\Products\CreateProductDTO;
use App\DTO\Products\UpdateProductDTO;
use App\Models\Product;

class ProductService
{
    //devuelve un objeto producto
    public function create(CreateProductDTO $data): Product
    {
        return Product::create($data->toArray());
    }

    public function update(UpdateProductDTO $data, Product $product): Product
    {
        //si no hay cambios devuelve el producto
        if (!$data->hasChanges()) {
            return $product;
        }
        //actualiza el producto
        $product->update($data->toArray());
        //devuelve el producto actualizado
        return $product
    }
}
