<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            //sometimes : valida solo si viene en la peticion
            "name" => "sometimes|required|string",
            "password" => "sometimes|required|string",
            "role" => "sometimes|required|string",
        ];
    }
    public function messages(): array
    {
        return [
            // Mensajes para el campo "name"
            'name.required' => 'El nombre no puede estar vacío si se envía en la petición.',
            'name.string' => 'El nombre debe ser una cadena de texto válida.',

            // Mensajes para el campo "password"
            'password.required' => 'La contraseña no puede estar vacía si se envía en la petición.',
            'password.string' => 'La contraseña debe ser una cadena de texto válida.',

            // Mensajes para el campo "role"
            'role.required' => 'El rol de usuario no puede estar vacío si se envía en la petición.',
            'role.string' => 'El rol debe ser una cadena de texto válida.',
        ];
    }
}
