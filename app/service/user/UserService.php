<?php

namespace App\service\user;

use App\DTO\User\CreateUserDTO;
use App\DTO\User\UpdateUserDTO;
use App\Models\User;

class UserService
{
    //funcion para crear usuario, recibe como parametro el dto, devuelve un objeto de tipo User
    public function create(CreateUserDTO $data): User
    {
        return User::create($data->toArray());
    }

    public function update(User $user, UpdateUserDTO $data): User
    {
        //verifica si no hay cambios devuvle el suario
        if (!$data->hasChanges()) {
            return $user;
        }
        //si hay cambios se actualiza el usuario
        $user->update($data->toArray());
        return $user;
    }
}
