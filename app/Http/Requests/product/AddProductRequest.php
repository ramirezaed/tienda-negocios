<?php

namespace App\Http\Requests\product;

use App\DTO\Products\CreateProductDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class AddProductRequest extends FormRequest
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
            "name" => "required|string|unique:products",
            "description" => "required|string",
            "price" => "required|decimal:0,2|gt:0",
            "stock" => "required|integer|gt:0",
            "category_id" => "required|integer|exists:categories,id",
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser texto.',
            "name.unique" => "ya existe un producto con ese nombre",

            'description.required' => 'La descripción es obligatoria.',
            'description.string' => 'La descripción debe ser texto.',

            'price.required' => 'El precio es obligatorio.',
            'price.decimal' => 'El precio debe tener hasta 2 decimales.',
            'price.gt' => 'El precio debe ser mayor a 0.',

            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.gt' => 'El stock debe ser mayor a 0.',

            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.integer' => 'La categoría debe ser un número entero.',
            'category_id.exists' => 'La categoría no existe.',
        ];
    }
    public function toDTO(): CreateProductDTO
    {
        return new CreateProductDTO(
            name: $this->input('name'),
            description: $this->input('description'),
            price: (float) $this->input('price'),
            stock: (int) $this->input('stock'),
            category_id: (int) $this->input('category_id'),
        );
    }
}
