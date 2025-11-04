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
// Error Handlers
// ============================================

$app->notFound(function () use ($app) {
    $app->response->setStatus(404);
    echo json_encode([
        'error' => '404 Not Found',
        'path' => $app->request->getPathInfo(),
        'method' => $app->request->getMethod(),
        'hint' => 'Try: ' . BASE_PATH . '/api/health'
    ], JSON_PRETTY_PRINT);
});

$app->error(function (Exception $e) use ($app) {
    $app->response->setStatus(500);
    
    $error = [
        'error' => 'Server Error',
        'message' => $e->getMessage(),
    ];
    
    if (getenv('APP_DEBUG') === 'true') {
        $error['file'] = $e->getFile();
        $error['line'] = $e->getLine();
        $error['trace'] = $e->getTraceAsString();
    }
    
    echo json_encode($error, JSON_PRETTY_PRINT);
});

return $app;