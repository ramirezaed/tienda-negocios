<?php

namespace App\Http\Resources\product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "price" => (float)$this->price,
            "stock" => $this->stock,
            "disponible" => $this->stock > 0, // devulve true
            "actualizado" => $this->updated_at->format("d/m/Y"),
            "category_id" => $this->category_id,
            "category name" => $this->category->name
        ];
    }
}
