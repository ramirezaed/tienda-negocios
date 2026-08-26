<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

//se definen los valores para guardar de forma masiva
#[Fillable(['name', 'email', 'password', 'role'])]
//se definen los campos ocultos, no se envian en la respuesta
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable  implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    use SoftDeletes; //importo para eliminacion logica

    // define el nombre en plural de la tabla que este modelo debe uasr
    protected $table = 'users';

    //Los atributos que deben ser convertidos a tipos de datos específicos.
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //implementa metodos para usar JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    //se define la relacion con la clase modelo
    //hasOne relacion 1<->1
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
