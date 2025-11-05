<?php

namespace App\Application\UseCases\Auth;

use App\Application\DTOs\LoginRequest;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Exceptions\InvalidCredentialsException;
use App\Infrastructure\Auth\PasswordHasher;
use App\Infrastructure\Auth\JwtTokenService;

/**
 * Login Use Case
 * Orchestrates the login process
 */
class LoginUseCase
{
    private UserRepositoryInterface $userRepository;
    private PasswordHasher $passwordHasher;
    private JwtTokenService $tokenService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordHasher $passwordHasher,
        JwtTokenService $tokenService
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->tokenService = $tokenService;
    }

    public function execute(LoginRequest $request): array
    {
        // Find user by email
        $user = $this->userRepository->findByEmail($request->email);
        
        if (!$user) {
            throw new InvalidCredentialsException('Invalid credentials');
        }

        // Verify password
        if (!$this->passwordHasher->verify($request->password, $user->getPassword())) {
            throw new InvalidCredentialsException('Invalid credentials');
        }

        // Generate token
        $token = $this->tokenService->generate($user);

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
