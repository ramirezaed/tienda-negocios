<?php

namespace App\Http\Requests\user;

use App\DTO\User\CreateUserDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string",
            //unique:users : se hace una consulta a la bd para comprabar que ese correo no este registrado
            "email" => "required|string|unique:users",
            "password" => "required|string",
            "role" => "required|string",
        ];
    }

    public function messages(): array
    {
        return [
            // Mensajes para el campo "name"
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto válida.',

            // Mensajes para el campo "email"
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto válida.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado en nuestro sistema.',

            // Mensajes para el campo "password"
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto válida.',

            // Mensajes para el campo "role"
            'role.required' => 'El rol de usuario es obligatorio.',
            'role.string' => 'El rol debe ser una cadena de texto válida.',
        ];
    }


    public function toDTO(): CreateUserDTO
    {
        return new CreateUserDTO(
            name: $this->input('name'),
            email: $this->input('email'),
            password: $this->input('password'),
            role: $this->input('role'),
        );
    }
}
