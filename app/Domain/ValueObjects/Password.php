<?php

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\InvalidPasswordException;

/**
 * Password Value Object
 * Enforces password rules
 */
final class Password
{
    private string $value;

    public function __construct(string $password, bool $isHashed = false)
    {
        if (!$isHashed && strlen($password) < 8) {
            throw new InvalidPasswordException("Password must be at least 8 characters");
        }
        $this->value = $password;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function fromHash(string $hash): self
    {
        return new self($hash, true);
    }
}
