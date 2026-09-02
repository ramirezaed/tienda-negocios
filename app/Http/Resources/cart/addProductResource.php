<?php

namespace App\Http\Resources\cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class addProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'product_id' => (int) $this->product_id,
            'quantity' => $this->quantity,
            'price' => (float) $this->price,
            'sub_total' => (float) $this->sub_total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
