<?php

namespace App\DTO\Products;

class ProductDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly float $price,
        public readonly int $stock,
        public readonly int $category_id
    ) {}

    public function toArray(): array
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'stock'       => $this->stock,
            "category_id" => $this->category_id
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data["name"],
            description: $data["description"] ?? null,
            price: $data["price"],
            stock: $data["stock"],
            category_id: $data["category_id"]
        );
    }
}
