<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    //se define el nombre en plural de la tabla que usara este modelo
    protected $table = "cart_items";
    //se define los campos para na insercion masiva
    protected $fillable = [
        "cart_id",
        "product_id",
        "quantity",
        "price",
    ];
    //un item pertenece a un carrito
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    //un item pertenece a un pructo
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
