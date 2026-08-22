<?php

namespace App\service\user;

use App\DTO\User\CreateUserDTO;
use App\Models\User;

class UserService
{
    //funcion para crear usuario, recibe como parametro el dto, devuelve un objeto de tipo User
    public function create(CreateUserDTO $data): User
    {
        return User::create($data->toArray());
    }
}
