<?php

namespace App\DTO\cart;


class removeProductsToCartDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $product_id,
        public readonly int $quantity
    ) {}

    public function toArray()
    {
        return [
            "product_id" => $this->product_id,
            "quantity" => $this->quantity
        ];
    }
    public static function fromArray(array $data): self
    {
        return new self(
            product_id: $data["product_id"],
            quantity: $data["quantity"]
        );
    }
}
