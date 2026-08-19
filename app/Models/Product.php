<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //se usa para eliminacion logica
    use SoftDeletes;
    //permite usar factory para cargar datos de prueba
    use HasFactory;

    //define que tabla usa este modelo
    protected $table = "products";
    protected $fillable = [
        "name",
        "description",
        "price",
        "stock",
        "category_id",
    ];



    //un producto pertenece a una categoria
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // un producto puede estar en muchos o ningun cart_items
    public function cartItem()
    {
        return $this->hasMany(CartItem::class);
    }
}
