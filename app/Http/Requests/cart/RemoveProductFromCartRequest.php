<?php

namespace App\Http\Requests\cart;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RemoveProductFromCartRequest extends FormRequest
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
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'El ID del producto es obligatorio.',
            'product_id.exists'   => 'El producto seleccionado no existe.',
            'quantity.required'   => 'La cantidad a quitar es obligatoria.',
            'quantity.integer'    => 'La cantidad debe ser un número entero.',
            'quantity.min'        => 'La cantidad a quitar debe ser al menos 1.',
        ];
    }
}
