<?php

namespace App\Http\Requests\checkout;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutFormRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'shipping_address' => 'required|string',
        ];
    }
    public function messages(): array
    {
        return [
            'user_id.required' => 'El campo usuario es obligatorio.',
            'user_id.integer' => 'El id de usuario debe ser un número entero.',
            'user_id.exists' => 'El usuario seleccionado no existe en nuestra base de datos.',
            'shipping_address.required' => 'La dirección de envío es obligatoria.',
            'shipping_address.string' => 'La dirección de envío debe ser un texto válido.',
        ];
    }
}
