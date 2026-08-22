<?php

namespace App\DTO\Categories;

class UpdateCategoryDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly ?string $name = null,
        public array $providedFields = [],
    ) {}

    //verifica si se enviaron datos en la peticion para modificar el producto
    public function hasChanges(): bool
    {
        return $this->providedFields !== [];
    }

    //devuelve solo los campos que el cliente envio en la peticion, 
    public function toArray()
    {
        //filtra y deja solo los datos que se envieron del cliente
        $values = [
            "name" => $this->name
        ];
        return array_intersect_key($values, array_flip($this->providedFields));
    }
}
