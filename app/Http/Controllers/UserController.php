<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //funcion para registrar un usuario
    public function register(Request $request)
    {
        //valida los datos que se ingresan
        $validateData = $request->validate([
            "name" => "required|string",
            //unique:users : se hace una consulta a la bd para comprabar que ese correo no este registrado
            "email" => "required|string|unique:users",
            "password" => "required|string",
            "role" => "required|strign",
        ]);

        try {
            $user = User::create($validateData);
            return Response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar registrar un usuario"], 500);
        }
    }
    //funcion  para modificar un usuario
    public function update(Request $request, int $id)
    {
        $validateData = $request->validate([
            //sometimes : valida solo si viene en la peticion
            "name" => "sometimes|required|string",
            "password" => "sometimes|required|string",
            "role" => "sometimes|required|string",
        ]);
        try {
            //busca el id en para ver si el usuario existe o no
            $user = User::find($id);
            //si el usario no existe
            if (!$user) {
                return response()->json(["message" => "usuario no encontrado"], 404);
            }
            //si el usuario existe valida los datos, actualiza el registro
            $user->update($validateData);
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "error interno al intentar actualizar un usuario"], 500);
        }
    }
    //funcion para mostrsr todos los usuarios
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
}
