<?php

namespace App\Application\DTOs;

use App\Domain\Entities\User;

class UserResponse
{
    public int $id;
    public string $email;
    public string $name;
    public string $created_at;

    public static function fromEntity(User $user): self
    {
        $response = new self();
        $response->id = $user->getId();
        $response->email = $user->getEmail();
        $response->name = $user->getName();
        $response->created_at = $user->getCreatedAt()->format('Y-m-d H:i:s');
        return $response;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'created_at' => $this->created_at,
        ];
    }
}
