<?php

namespace App\Application\UseCases\Auth;

use App\Application\DTOs\RegisterRequest;
use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;

class RegisterUseCase
{
    private UserRepositoryInterface $userRepository;
    private $passwordHasher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        $passwordHasher
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function execute(RegisterRequest $request): User
    {
        // Check if user already exists
        if ($this->userRepository->findByEmail($request->email)) {
            throw new \DomainException('Email already registered');
        }

        // Hash password
        $hashedPassword = $this->passwordHasher->hash($request->password);

        // Create user entity
        $user = new User(
            $request->email,
            $hashedPassword,
            $request->name
        );

        // Save user
        return $this->userRepository->save($user);
    }
}
