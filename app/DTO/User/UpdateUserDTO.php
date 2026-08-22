<?php

namespace App\DTO\User;

class UpdateUserDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $email,
        public readonly ?string $role,
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
            'email'       => $this->email,
            'role'        => $this->role,

        ];
        //filtra y deja solo los datos que se envieron del cliente
        return array_intersect_key(
            $values,
            array_flip($this->providedFields),
        );
    }
}
