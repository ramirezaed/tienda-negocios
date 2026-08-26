<?php

namespace App\service\auth;

use App\DTO\auth\RegisterDTO;
use App\Models\User;

class authService
{
    //funcion para crear usuario, recibe como parametro el dto, devuelve un objeto de tipo User
    public function create(RegisterDTO $data): User
    {
        return User::create($data->toArray());
    }
}
