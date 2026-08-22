<?php

namespace App\Http\Requests\category;

use App\DTO\Categories\CreateCategoryDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;


class AddCategoryRequest extends FormRequest
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
            "name" => "required|string|unique:categories"
        ];
    }
    //mensajes que se muestran si no se cumple alguna regla
    public function messages(): array
    {
        return  [
            // Mensajes para el campo "name"
            'name.required' => 'El nombre de la categoria es obligatorio.',
            'name.string' => 'El nombre de la categoria debe ser una cadena de texto válida.',
        ];
    }

    public function toDTO(): CreateCategoryDTO
    {
        return new CreateCategoryDTO(
            name: $this->input("name")
        );
    }
}
