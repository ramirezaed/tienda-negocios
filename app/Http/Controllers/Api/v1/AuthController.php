<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginFormRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\service\auth\authService;

class AuthController extends Controller
{
    //funcion para iniciar sesion
    public function __construct(private authService $auth_service) {}

    public  function login(LoginFormRequest $request)
    {
        $credentials = $request->validated();
        $token = auth('api')->attempt($credentials);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            "user" => auth("api")->user()
        ]);
    }

    //funcion para registrarse
    public function register(RegisterRequest $request)
    {
        $user = $this->auth_service->create($request->toDTO());
        $token = auth("api")->login($user);

        return response()->json([
            "acces_token" => $token,
            "token_type" => 'bearer',
            "expires_in" => auth('api')->factory()->getTTL() * 60,
            "user" => $user
        ]);
    }

    //funcion para ver mi perfil
    public function profile()
    {
        return response()->json(auth('api')->user());
    }
}
