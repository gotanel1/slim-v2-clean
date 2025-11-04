<?php

namespace App\Infrastructure\Http\Validators;

use Respect\Validation\Validator as v;

class LoginValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if (!v::email()->validate($data['email'] ?? '')) {
            $errors['email'] = 'Invalid email format';
        }

        if (!v::notEmpty()->validate($data['password'] ?? '')) {
            $errors['password'] = 'Password is required';
        }

        return $errors;
    }
}
