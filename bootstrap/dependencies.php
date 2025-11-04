<?php

// Infrastructure Services
$app->container->singleton('PasswordHasher', function () {
    return new \App\Infrastructure\Auth\PasswordHasher();
});

$app->container->singleton('JwtTokenService', function () {
    return new \App\Infrastructure\Auth\JwtTokenService();
});

// Repositories
$app->container->singleton('UserRepository', function () {
    return new \App\Infrastructure\Persistence\Eloquent\Repositories\EloquentUserRepository();
});

// Validators
$app->container->singleton('LoginValidator', function () {
    return new \App\Infrastructure\Http\Validators\LoginValidator();
});

$app->container->singleton('RegisterValidator', function () {
    return new \App\Infrastructure\Http\Validators\RegisterValidator();
});

// Use Cases (ถ้าต้องการ)
$app->container->singleton('LoginUseCase', function ($c) {
    return new \App\Application\UseCases\Auth\LoginUseCase(
        $c['UserRepository'],
        $c['PasswordHasher'],
        $c['JwtTokenService']
    );
});

$app->container->singleton('RegisterUseCase', function ($c) {
    return new \App\Application\UseCases\Auth\RegisterUseCase(
        $c['UserRepository'],
        $c['PasswordHasher']
    );
});