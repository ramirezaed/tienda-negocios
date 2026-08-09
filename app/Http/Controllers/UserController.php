<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
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
}
