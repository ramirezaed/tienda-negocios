<?php

namespace App\service\auth;

use App\DTO\Auth\LoginDTO;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginService
{
    public function login(LoginDTO $credentials): string
    {
        $token = auth('api')->attempt($credentials->toArray());

        if (!$token) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Credenciales incorrectas.',
                    'status' => 401,
                    'error' => (object)[],
                ], 401)
            );
        }

        return $token;
    }
}
