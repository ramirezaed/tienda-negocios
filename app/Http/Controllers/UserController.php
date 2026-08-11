<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\service\UserService;

class UserController extends Controller
{
    //inyecta el servicio en el contructor
    //constructor se ejecuta automaticamente 
    public function __construct(private UserService $userService) {}
    //funcion para mostrsr todos los usuarios
    public function index()
    {
        //muestra lista de usuarios, de 10 en 10
        $users = User::paginate(10);
        return response()->json($users);
    }

    //funcion para registrar un usuario
    //store es el nonbre standar para registrar
    public function store(AddUserRequest $request)
    {
        $user = User::create($request->validated());
        return Response()->json($user, 201);
    }

    //funcion  para modificar un usuario
    public function update(UpdateUserRequest $request, int $id)
    {
        //llama al servicio findbyid
        $user = $this->userService->findById($id);
        //si el usuario existe valida los datos, actualiza el registro
        $user->update($request->validated());
        return response()->json($user, 200);
    }

    //funcion para elimiar un usuario
    public function destroy(int $id)
    {
        //lama al servicio buscar por id
        $user = $this->userService->findById($id);
        $user->delete();
        return response()->json(["message" => "usuario eliminado con exito"], 200);
    }

    //funcion para obtener un los datos de un usuario
    public function show(int $id)
    {
        $user = $this->userService->findById($id);
        return response()->json($user);
    }
}
