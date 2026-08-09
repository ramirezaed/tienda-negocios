<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //dfine el nombre de la tabla que usara este modelo
    protected $table = "Categories";
    //define los campos para insercion masiva
    protected $fillable = [
        "name",
    ];

    //define relacion con producto
    //una categoria puede tener muchos productos 1->N
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
