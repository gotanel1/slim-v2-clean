<?php

namespace App\Application\DTOs;

class RegisterRequest
{
    public string $email;
    public string $password;
    public string $name;

    public function __construct(string $email, string $password, string $name)
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['name'] ?? ''
        );
    }
}
