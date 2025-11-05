<?php

namespace App\Infrastructure\Http\Middleware;

/**
 * Error Handler Middleware
 * Wraps route execution and converts exceptions to proper HTTP responses
 */
class ErrorHandlerMiddleware
{
    public function __invoke($request, $response, $next)
    {
        try {
            // Execute route
            return $next($request, $response);
            
        } catch (\App\Domain\Exceptions\InvalidCredentialsException $e) {
            // 401 Unauthorized
            return $this->jsonResponse($response, [
                'error' => [
                    'code' => 'INVALID_CREDENTIALS',
                    'message' => $e->getMessage(),
                    'status' => 401
                ]
            ], 401);
            
        } catch (\App\Domain\Exceptions\UserNotFoundException $e) {
            // 404 Not Found
            return $this->jsonResponse($response, [
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => $e->getMessage(),
                    'status' => 404
                ]
            ], 404);
            
        } catch (\DomainException $e) {
            // 400 Bad Request
            return $this->jsonResponse($response, [
                'error' => [
                    'code' => 'DOMAIN_ERROR',
                    'message' => $e->getMessage(),
                    'status' => 400
                ]
            ], 400);
            
        } catch (\InvalidArgumentException $e) {
            // 422 Unprocessable Entity
            return $this->jsonResponse($response, [
                'error' => [
                    'code' => 'INVALID_ARGUMENT',
                    'message' => $e->getMessage(),
                    'status' => 422
                ]
            ], 422);
            
        } catch (\Exception $e) {
            // Let global handler deal with unexpected errors
            throw $e;
        }
    }
    
    private function jsonResponse($response, array $data, int $status)
    {
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatus($status);
        $response->setBody(json_encode($data, JSON_PRETTY_PRINT));
        return $response;
    }
}
