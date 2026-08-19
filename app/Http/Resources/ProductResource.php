<?php

namespace App\Http\Resources;

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
            "description" => $this->descriptions,
            "price" => (float)$this->price,
            "stock" => $this->stock,
            "disponible" => $this->stock > 0, // devulve true
            "actualizado" => $this->updated_at->format("d/m/Y")
        ];
    }
}
