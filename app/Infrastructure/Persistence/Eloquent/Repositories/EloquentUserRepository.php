<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\UserModel;

/**
 * Eloquent User Repository Implementation
 * Maps between Eloquent Models and Domain Entities
 */
class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        $model = UserModel::find($id);
        return $model ? $this->toDomainEntity($model) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $model = UserModel::where('email', $email)->first();
        return $model ? $this->toDomainEntity($model) : null;
    }

    public function save(User $user): User
    {
        $model = new UserModel([
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'name' => $user->getName(),
        ]);
        $model->save();

        return $this->toDomainEntity($model);
    }

    public function update(User $user): bool
    {
        $model = UserModel::find($user->getId());
        if (!$model) {
            return false;
        }

        $model->email = $user->getEmail();
        $model->password = $user->getPassword();
        $model->name = $user->getName();
        
        return $model->save();
    }

    public function delete(int $id): bool
    {
        return UserModel::destroy($id) > 0;
    }

    public function all(): array
    {
        return UserModel::all()
            ->map(fn($model) => $this->toDomainEntity($model))
            ->toArray();
    }

    private function toDomainEntity(UserModel $model): User
    {
        return new User(
            $model->email,
            $model->password,
            $model->name,
            $model->id,
            new \DateTime($model->created_at),
            new \DateTime($model->updated_at)
        );
    }
}
