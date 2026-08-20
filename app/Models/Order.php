<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        "user_id",
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
