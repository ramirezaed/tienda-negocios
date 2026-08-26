<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginFormRequest;
use Illuminate\Http\Request;

class AuhtController extends Controller
{
    //funcion para iniciar sesion
    public  function login(LoginFormRequest $request) {}

    //funcion para registrarse
    public function register() {}

    //funcion para ver mi perfil
    public function profile() {}
}
