<?php

namespace App\Infrastructure\Http\Middleware;

/**
 * CORS Middleware for Slim 2.x
 */
class CorsMiddleware extends \Slim\Middleware
{
    public function call()
    {
        // Add CORS headers
        $this->app->response->headers->set('Access-Control-Allow-Origin', '*');
        $this->app->response->headers->set('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization');
        $this->app->response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        
        // Handle OPTIONS request
        if ($this->app->request->isOptions()) {
            $this->app->response->setStatus(200);
            return;
        }
        
        // Call next middleware
        $this->next->call();
    }
}
