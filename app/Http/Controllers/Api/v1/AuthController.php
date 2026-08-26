<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginFormRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Resources\auth\loginResource;
use App\Http\Resources\auth\profileResorce;
use App\Http\Resources\auth\registerResorce;
use App\service\auth\authService;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    //funcion para iniciar sesion
    public function __construct(private authService $auth_service) {}

    public  function login(LoginFormRequest $request): JsonResponse
    {
        $credentials = $request->toDTO();
        $token = auth('api')->attempt($credentials->toArray());
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            "user" => new loginResource(auth("api")->user())
        ]);
    }

    //funcion para registrarse
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->auth_service->create($request->toDTO());
        $token = auth("api")->login($user);

        return response()->json([
            "access_token" => $token,
            "token_type" => 'bearer',
            "expires_in" => auth('api')->factory()->getTTL() * 60,
            "user" => new registerResorce($user)
        ]);
    }

    //funcion para ver mi perfil
    public function profile(): JsonResponse
    {
        $user = auth("api")->user();
        return response()->json(new profileResorce($user));
    }
}
