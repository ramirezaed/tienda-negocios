<?php

namespace App\DTO\Categories;

class CategoryDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $name
    ) {}

    public function toArray()
    {
        return [
            "name" => $this->name
        ];
    }
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data["name"]
        );
    }
}
