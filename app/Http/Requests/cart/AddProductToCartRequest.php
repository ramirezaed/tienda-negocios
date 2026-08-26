<?php

namespace App\Http\Requests\cart;

use App\DTO\cart\addProductsToCartDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddProductToCartRequest extends FormRequest
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


    //valida que los datos recibidos sean correctos
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];
    }


    public function messages(): array
    {
        return [
            'product_id.required' => 'El producto es obligatorio.',
            'product_id.integer' => 'El producto debe ser un número entero.',
            'product_id.exists' => 'El producto no existe.',

            'quantity.required' => 'La cantidad es obligatoria.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad debe ser como mínimo 1.',
        ];
    }
    public function toDTO(): addProductsToCartDTO
    {
        return new addProductsToCartDTO(
            product_id: $this->input("product_id"),
            quantity: $this->input("quantity")
        );
    }
}
