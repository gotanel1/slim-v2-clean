<?php

namespace App\Infrastructure\Http\Validators;

use Respect\Validation\Validator as v;

class RegisterValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if (!v::email()->validate($data['email'] ?? '')) {
            $errors['email'] = 'Invalid email format';
        }

        if (!v::stringType()->length(8, null)->validate($data['password'] ?? '')) {
            $errors['password'] = 'Password must be at least 8 characters';
        }

        if (!v::stringType()->length(2, 100)->validate($data['name'] ?? '')) {
            $errors['name'] = 'Name must be between 2 and 100 characters';
        }

        return $errors;
    }
}
