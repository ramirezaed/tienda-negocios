<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //    //dfine el nombre de la tabla que usara este modelo
    protected $table = "orders";

    protected $fillable = [
        "user_id",
        "shipping_address",
        "sub_total",
        "tax", // impuesto
        "shipping_cost", //costo de envio
        "total"
    ];

    public function items()
    {
        //una order puede tener muchos items
        return $this->hasMany(OrderItem::class);
    }
}
