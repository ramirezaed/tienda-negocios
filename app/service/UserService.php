<?php

namespace App\service;

use App\Exceptions\UserNotFoundException;
use App\Models\User;

class UserService
{
    //servicio para buscar usuario por id, servicio reutilizable, para show, update, destroy
    //user al final asegura que devulva un objeto de tipo User
    public function findById(int $id): User
    {
        //busca el usuario y verifica si existe
        $user = User::find($id);
        if (!$user) {
            throw new UserNotFoundException();
        }
        return $user;
    }
}
