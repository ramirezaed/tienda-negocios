<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    //funcion para mostrsr todos los usuarios
    public function index(): JsonResponse
    {
        $users = User::paginate(10);
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
