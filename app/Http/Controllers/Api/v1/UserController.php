<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserIndexFormRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    //funcion para mostrsr todos los usuarios
    public function index(UserIndexFormRequest $request): JsonResponse
    {

        //crea la consulta en el modelo user -> "select * from user"
        $users = User::query()
            //search es el parametro que se busca
            //cuando existe search se ejecuta la funcion
            ->when($request->query('search'), function ($query, $search) {
                //agega condicion where a la consulta, usa el valor search
                $query->where(function ($query) use ($search) {
                    //se busca usuario cunado el nombre = search o email =search
                    $query->where("name", "like", "%{$search}%")->orWhere("email",  "like", "%{$search}%");
                });
                //si search es nulo, devuelve la lista de usuarios paginado de a 10
            })->paginate(10);

        return response()->json($users);
    }

    //funcion para registrar un usuario
    //store es el nonbre standar para registrar
    public function store(AddUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        return Response()->json($user, 201);
    }

    //funcion para obtener un los datos de un usuario
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    //funcion  para modificar un usuario
    public function update(UpdateUserRequest $request, User $user)
    {
        //si el usuario existe valida los datos, actualiza el registro
        $user->update($request->validated());
        return response()->json($user, 200);
    }

    //funcion para elimiar un usuario
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(["message" => "usuario eliminado con exito"], 200);
    }
}
