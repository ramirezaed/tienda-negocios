<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    //funcion para registrar un usuario
    //store es el nonbre standar para registrar
    public function store(AddUserRequest $request)
    {
        try {
            $user = User::create($request->validated());
            return Response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar registrar un usuario"], 500);
        }
    }

    //funcion  para modificar un usuario
    public function update(UpdateUserRequest $request, int $id)
    {
        try {
            //busca el id en para ver si el usuario existe o no
            $user = User::find($id);
            //si el usario no existe
            if (!$user) {
                return response()->json(["message" => "usuario no encontrado"], 404);
            }
            //si el usuario existe valida los datos, actualiza el registro
            $user->update($request->validated());
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar actualizar un usuario"], 500);
        }
    }
    //funcion para mostrsr todos los usuarios
    public function index()
    {
        try {
            //muestra lista de usuarios, de 10 en 10
            $users = User::paginate(10);
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar obtener lista de usuarios"], 500);
        }
    }
    //funcion para elimiar un usuario
    public function delete(int $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(["message" => "usuario no encontrado"], 404);
            }
            $user->delete();
            return response()->json(["message" => "usuario eliminado con exito"], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar eliminar al usuario"], 500);
        }
    }

    //funcion para obtener un los datos de un usuario
    public function getById(int $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(["message" => "usuario no encontrado"], 404);
            }
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar buscar al usuario"], 500);
        }
    }
}
