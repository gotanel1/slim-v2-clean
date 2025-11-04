<?php

namespace App\Infrastructure\Http\Middleware;

use App\Infrastructure\Auth\JwtTokenService;

/**
 * Authentication Middleware
 * Validates JWT tokens
 */
class AuthMiddleware
{
    private JwtTokenService $tokenService;

    public function __construct(JwtTokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function __invoke($request, $response, $next)
    {
        $authHeader = $request->getHeader('Authorization');
        
        if (!$authHeader) {
            return $response->withJson(['error' => 'Unauthorized'], 401);
        }

        $token = str_replace('Bearer ', '', $authHeader[0]);

        try {
            $decoded = $this->tokenService->decode($token);
            $request = $request->withAttribute('user_id', $decoded->sub);
            return $next($request, $response);
        } catch (\Exception $e) {
            return $response->withJson(['error' => 'Invalid token'], 401);
        }
    }
}
