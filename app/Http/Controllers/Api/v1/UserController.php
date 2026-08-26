<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\AddUserRequest;
use App\Http\Requests\search\FilterSearchFormRequest;
use App\Http\Requests\user\UpdateUserRequest;
use App\Http\Resources\user\UserResource;
use App\Models\User;
use App\service\user\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class UserController extends Controller
{

    public function __construct(private UserService $userService) {}

    //funcion para mostrsr todos los usuarios
    public function index(FilterSearchFormRequest $request): AnonymousResourceCollection
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

        return UserResource::collection($users);
    }

    //funcion para registrar un usuario
    //store es el nonbre standar para registrar
    public function store(AddUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->toDTO());
        return response()->json(new UserResource($user), 201);
    }

    //funcion para obtener un los datos de un usuario
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    //funcion  para modificar un usuario
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        //si el usuario existe valida los datos, actualiza el registro
        $user = $this->userService->update($user, $request->toDTO());
        return Response()->json(new UserResource($user), 200);
    }

    //funcion para elimiar un usuario
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(["message" => "usuario eliminado con exito"], 200);
    }
}
