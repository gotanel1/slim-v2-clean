<?php

namespace App\Domain\Entities;

/**
 * User Entity - Core Business Object
 * Pure domain logic, no framework dependencies
 */
class User
{
    private ?int $id;
    private string $email;
    private string $password;
    private string $name;
    private ?\DateTime $createdAt;
    private ?\DateTime $updatedAt;

    public function __construct(
        string $email,
        string $password,
        string $name,
        ?int $id = null,
        ?\DateTime $createdAt = null,
        ?\DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->createdAt = $createdAt ?? new \DateTime();
        $this->updatedAt = $updatedAt;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    // Business Methods
    public function updateProfile(string $name): void
    {
        $this->name = $name;
        $this->updatedAt = new \DateTime();
    }

    public function changePassword(string $newPassword): void
    {
        // Add business rules here
        $this->password = $newPassword;
        $this->updatedAt = new \DateTime();
    }

    // ⭐ แก้ไขตรงนี้ - PHP 7.4 Compatible
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'created_at' => $this->createdAt ? $this->createdAt->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updatedAt ? $this->updatedAt->format('Y-m-d H:i:s') : null,
        ];
    }
}
