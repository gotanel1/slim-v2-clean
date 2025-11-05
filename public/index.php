<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_PATH', '/service');

// Remove /service from REQUEST_URI for Slim
$requestUri = $_SERVER['REQUEST_URI'];
if (strpos($requestUri, BASE_PATH) === 0) {
    $_SERVER['REQUEST_URI'] = substr($requestUri, strlen(BASE_PATH));
}

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Run: composer install']));
}

require __DIR__ . '/../vendor/autoload.php';

// Load .env and sync to putenv for getenv() compatibility
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
    
    // Sync $_ENV to putenv() so getenv() works
    foreach ($_ENV as $key => $value) {
        if (!is_array($value) && $value !== null) {
            putenv("{$key}={$value}");
        }
    }
    
} catch (Exception $e) {}

// Bootstrap application
$app = require __DIR__ . '/../bootstrap/app.php';

// Load dependencies
require __DIR__ . '/../bootstrap/dependencies.php';

// ============================================
// Add Middleware (Order matters!)
// ============================================

// 1. CORS Middleware (first - handles preflight)
$app->add(new \App\Infrastructure\Http\Middleware\CorsMiddleware());

// 2. Error Handler Middleware (second - wraps all routes)
$app->add(new \App\Infrastructure\Http\Middleware\ErrorHandlerMiddleware());

// Load routes
require __DIR__ . '/../routes/api.php';

// Run application
$app->run();