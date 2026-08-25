<?php

namespace App\Http\Requests\product;

use App\DTO\Products\UpdateProductDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            //sometimes solo valida cuando se los envian
            "name" => "sometimes|required|string",
            "description" => "sometimes|required|string",
            "price" => "sometimes|required|decimal:0,2|gt:0", //gt: 0 es para verificar que sea mayor que 0
            "stock" => "sometimes|required|integer|gt:0",
            "category_id" => "sometimes|required|integer|exists:categories,id"
        ];
    }

    public function messages(): array
    {
        return [
            // Mensajes para el campo "name"
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.string' => 'El nombre del producto debe ser una cadena de texto válida.',

            // Mensajes para el campo "description"
            'description.required' => 'La descripción del producto es obligatoria.',
            'description.string' => 'La descripción debe ser una cadena de texto válida.',

            // Mensajes para el campo "price"
            'price.required' => 'El precio del producto es obligatorio.',
            'price.decimal' => 'El precio debe ser un número decimal con un máximo de 2 dígitos (ejemplo: 10.50 o 99).',
            'price.gt' => 'El precio debe ser un monto mayor a 0.',

            // Mensajes para el campo "stock"
            'stock.required' => 'El stock del producto es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero válido.',
            'stock.gt' => 'El stock disponible debe ser mayor a 0.',

            // Mensajes para el campo "category_id"
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.integer' => 'El identificador de la categoría debe ser un número entero.',
            'category_id.exists' => 'La categoría seleccionada no existe en nuestro sistema.',
        ];
    }

    public function toDTO(): UpdateProductDTO
    {
        $validated = $this->validated();

        return new UpdateProductDTO(
            name: $validated['name'] ?? null,
            description: $validated['description'] ?? null,
            //array list solo se usa con datos float o int
            price: array_key_exists('price', $validated)
                ? (float) $validated['price']
                : null,
            stock: array_key_exists('stock', $validated)
                ? (int) $validated['stock']
                : null,
            category_id: array_key_exists('category_id', $validated)
                ? (int) $validated['category_id']
                : null,
            providedFields: array_keys($validated),
        );
    }
}
