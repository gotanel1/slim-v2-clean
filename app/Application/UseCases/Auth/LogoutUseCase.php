<?php

namespace App\Application\UseCases\Auth;

class LogoutUseCase
{
    public function execute(string $token): bool
    {
        // Implement token blacklist logic here
        // For now, just return true
        return true;
    }
}
