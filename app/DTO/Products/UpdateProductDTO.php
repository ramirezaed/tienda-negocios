<?php

namespace App\DTO\Products;

class UpdateProductDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?float $price = null,
        public readonly ?int $stock = null,
        public readonly ?int $category_id = null,
        public array $providedFields = [],
    ) {}

    //verifica si se enviaron datos en la peticion para modificar el producto
    public function hasChanges(): bool
    {
        return $this->providedFields !== [];
    }

    //devuelve solo los campos que el cliente envio en la peticion, 
    public function toArray(): array
    {
        $values = [
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'stock'       => $this->stock,
            "category_id" => $this->category_id
        ];
        //filtra y deja solo los datos que se envieron del cliente
        return array_intersect_key(
            $values,
            array_flip($this->providedFields),
        );
    }
}
