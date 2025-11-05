<?php

// Create Slim application
$app = new \Slim\Slim([
    'debug' => getenv('APP_DEBUG') === 'true',
    'mode' => getenv('APP_ENV') ?: 'production',
]);

// Set JSON content type for all responses
$app->response->headers->set('Content-Type', 'application/json; charset=utf-8');

// ============================================
// Setup SQL Server Database Connection
// ============================================

try {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    
    $capsule->addConnection([
        'driver' => getenv('DB_CONNECTION') ?: 'sqlsrv',
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: '1433',
        'database' => getenv('DB_DATABASE') ?: 'service_db',
        'username' => getenv('DB_USERNAME') ?: 'sa',
        'password' => getenv('DB_PASSWORD') ?: '',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
    ]);
    
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    
} catch (Exception $e) {
    // Database not configured yet
}

// ============================================
// Error Handlers (Global - Last Resort)
// ============================================

$app->notFound(function () use ($app) {
    $app->response->setStatus(404);
    echo json_encode([
        'error' => [
            'code' => 'NOT_FOUND',
            'message' => '404 Not Found',
            'path' => $app->request->getPathInfo(),
            'method' => $app->request->getMethod(),
            'status' => 404,
            'hint' => 'Try: ' . BASE_PATH . '/api/health'
        ]
    ], JSON_PRETTY_PRINT);
});

$app->error(function (Exception $e) use ($app) {
    // Log the error
    error_log(sprintf(
        "[%s] %s: %s in %s:%d\nStack trace:\n%s",
        date('Y-m-d H:i:s'),
        get_class($e),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    ));
    
    // Determine status code based on exception type
    $statusCode = 500;
    $errorCode = 'INTERNAL_SERVER_ERROR';
    
    if ($e instanceof \Illuminate\Database\QueryException) {
        $statusCode = 503;
        $errorCode = 'DATABASE_ERROR';
    }
    
    $app->response->setStatus($statusCode);
    
    $error = [
        'error' => [
            'code' => $errorCode,
            'message' => getenv('APP_DEBUG') === 'true' 
                ? $e->getMessage() 
                : 'An unexpected error occurred',
            'status' => $statusCode
        ]
    ];
    
    // Only show details in debug mode
    if (getenv('APP_DEBUG') === 'true') {
        $error['error']['details'] = [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => explode("\n", $e->getTraceAsString())
        ];
    }
    
    echo json_encode($error, JSON_PRETTY_PRINT);
});

return $app;