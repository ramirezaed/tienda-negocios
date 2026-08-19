<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    //se usa para eliminacion logica
    use SoftDeletes;
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
