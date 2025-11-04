<?php

namespace App\Infrastructure\Auth;

/**
 * Password Hasher Service
 * Handles password hashing and verification
 */
class PasswordHasher
{
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
