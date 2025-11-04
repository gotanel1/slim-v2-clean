<?php

namespace App\Presentation\Http\Presenters;

class JsonPresenter
{
    public function success($data, int $statusCode = 200): array
    {
        return [
            'success' => true,
            'data' => $data,
            'status' => $statusCode
        ];
    }

    public function error(string $message, int $statusCode = 400): array
    {
        return [
            'success' => false,
            'error' => $message,
            'status' => $statusCode
        ];
    }

    public function validationError(array $errors): array
    {
        return [
            'success' => false,
            'errors' => $errors,
            'status' => 422
        ];
    }
}
