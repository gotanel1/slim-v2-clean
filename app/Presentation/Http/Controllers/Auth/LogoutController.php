<?php

namespace App\Presentation\Http\Controllers\Auth;

class LogoutController
{
    public function __invoke($request, $response)
    {
        // Implement logout logic (token blacklist) here
        $response->headers->set('Content-Type', 'application/json');
        echo json_encode(['message' => 'Logout successful']);
    }
}
