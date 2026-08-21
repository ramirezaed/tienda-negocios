<?php

namespace App\DTO\User;

class UserDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role,
    ) {}

    public function toArray(): array
    {
        return [
            'name'        => $this->name,
            'email' => $this->email,
            'password'       => $this->password,
            'role'       => $this->role,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data["name"],
            email: $data["email"],
            password: $data["password"],
            role: $data["role"],
        );
    }
}
