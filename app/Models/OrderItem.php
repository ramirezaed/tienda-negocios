<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //define la tabla que usa este modelo
    protected $table = "order_items";
    protected $fillable = [
        "order_id",
        "product_id",
        "quantity",
        "price",
        "sub_total"
    ];

    public function product()
    {
        //cada items pertenece a un producto
        return $this->belongsTo(Product::class);
    }
}
