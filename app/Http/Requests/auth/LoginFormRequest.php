<?php

namespace App\Http\Requests\auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginFormRequest extends FormRequest
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
            "email" => "required|email|exists:users,email",
            "password" => "required|string"
        ];
    }
    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email'    => 'El formato del correo electrónico no es válido.',
            'email.exists'   => 'Datos Incorrectos.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.string'   => 'La contraseña debe ser una cadena de texto.',
        ];
    }
}
