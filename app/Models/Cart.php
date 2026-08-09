<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    //Se define el nombre de la tabla que debe usar este modelo(evitar que busque carts)
    protected $table = "cart";
    //se  define los campos para la insercion masiva
    protected $fillable = [
        "user_id",
    ];

    //se define la relacioon inversa con usuario
    //un carrito pertenece a un usuario 1 <-> 1
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //se define la recion con los items del carrito
    //un carrito tiene muchos items 1->N
    public function items()
    {
        return $this->HasMany(CartItem::class);
    }
}
