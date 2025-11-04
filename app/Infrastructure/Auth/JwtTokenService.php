<?php

namespace App\Infrastructure\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Domain\Entities\User;

/**
 * JWT Token Service
 * Handles token generation and validation
 */
class JwtTokenService
{
    private string $secretKey;
    private int $expirationTime;

    public function __construct()
    {
        $this->secretKey = $_ENV['JWT_SECRET'] ?? 'your-secret-key';
        $this->expirationTime = (int) ($_ENV['JWT_EXPIRATION'] ?? 3600);
    }

    public function generate(User $user): string
    {
        $payload = [
            'iss' => $_ENV['APP_URL'] ?? 'localhost',
            'iat' => time(),
            'exp' => time() + $this->expirationTime,
            'sub' => $user->getId(),
            'email' => $user->getEmail(),
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function decode(string $token): object
    {
        return JWT::decode($token, new Key($this->secretKey, 'HS256'));
    }

    public function validate(string $token): bool
    {
        try {
            $this->decode($token);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
