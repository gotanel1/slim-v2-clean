<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\User;

/**
 * User Repository Interface
 * Define contract without implementation
 */
interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function save(User $user): User;
    public function update(User $user): bool;
    public function delete(int $id): bool;
    public function all(): array;
}
