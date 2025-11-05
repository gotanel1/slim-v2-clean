<?php

namespace App\Infrastructure\Http\Middleware;

/**
 * Error Handler Middleware
 * Wraps route execution and converts exceptions to proper HTTP responses
 * 
 * For Slim 2.x - extends \Slim\Middleware
 */
class ErrorHandlerMiddleware extends \Slim\Middleware
{
    public function call()
    {
        try {
            // Execute next middleware/route
            $this->next->call();
            
        } catch (\App\Domain\Exceptions\InvalidCredentialsException $e) {
            // 401 Unauthorized
            $this->jsonResponse([
                'error' => [
                    'code' => 'INVALID_CREDENTIALS',
                    'message' => $e->getMessage(),
                    'status' => 401
                ]
            ], 401);
            
        } catch (\App\Domain\Exceptions\UserNotFoundException $e) {
            // 404 Not Found
            $this->jsonResponse([
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => $e->getMessage(),
                    'status' => 404
                ]
            ], 404);
            
        } catch (\DomainException $e) {
            // 400 Bad Request
            $this->jsonResponse([
                'error' => [
                    'code' => 'DOMAIN_ERROR',
                    'message' => $e->getMessage(),
                    'status' => 400
                ]
            ], 400);
            
        } catch (\InvalidArgumentException $e) {
            // 422 Unprocessable Entity
            $this->jsonResponse([
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
    
    private function jsonResponse(array $data, int $status)
    {
        $this->app->response->headers->set('Content-Type', 'application/json');
        $this->app->response->setStatus($status);
        $this->app->response->setBody(json_encode($data, JSON_PRETTY_PRINT));
    }
}
