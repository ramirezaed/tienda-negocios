<?php

namespace App\Http\Requests\category;

use App\DTO\Categories\UpdateCategoryDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            "name" => "sometimes|required|unique:categories"
        ];
    }
    public function messages(): array
    {
        return [
            // Mensajes para el campo "name"
            'name.required' => 'El nombre no puede estar vacío si se envía en la petición.',
            'name.string' => 'El nombre debe ser una cadena de texto válida.',
        ];
    }
    public function toDTO(): UpdateCategoryDTO
    {
        $validated = $this->validated();
        return new UpdateCategoryDTO(
            name: $validated['name'] ?? null,
            providedFields: array_keys($validated),
        );
    }
}
