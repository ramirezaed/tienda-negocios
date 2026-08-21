<?php

namespace App\Http\Requests\cart;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ClearCartRequest extends FormRequest
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
        return ['user_id' => 'required|integer|exists:users,id'];
    }
    public function messages(): array
    {
        return [
            'user_id.required' => 'El usuario es obligatorio.',
            'user_id.integer' => 'El usuario debe ser un número entero.',
            'user_id.exists' => 'El usuario no existe.',
        ];
    }
}
