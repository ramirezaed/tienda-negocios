<?php

namespace App\service;

use App\Exceptions\ProductNotFoundException;
use App\Models\Product;


class ProductService
{
    //servicio para buscar producto por id, servicio reutilizable, para show, update, destroy
    //Produc al final asegura que devulva un objeto de tipo product
    public function findById(int $id): Product
    {
        //busca el usuario y verifica si existe
        $product = Product::find($id);
        if (!$product) {
            throw new ProductNotFoundException();
        }
        return $product;
    }
}
