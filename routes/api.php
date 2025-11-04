<?php

// Root
$app->get('/', function () use ($app) {
    echo json_encode([
        'app' => 'Clean Architecture API',
        'version' => '1.0.0',
        'status' => 'running',
        'database' => 'SQL Server 2008 R2',
        'endpoints' => [
            'health' => BASE_PATH . '/api/health',
            'auth_test' => BASE_PATH . '/api/auth/test',
            'register' => BASE_PATH . '/api/auth/register',
            'login' => BASE_PATH . '/api/auth/login',
        ]
    ], JSON_PRETTY_PRINT);
});

// API Group
$app->group('/api', function () use ($app) {
    
    // Health Check
    $app->get('/health', function () use ($app) {
        $dbStatus = 'not configured';
        $dbVersion = 'unknown';
        
        try {
            $pdo = \Illuminate\Database\Capsule\Manager::connection()->getPdo();
            $dbStatus = 'connected';
            
            $stmt = $pdo->query("SELECT @@VERSION as version");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $dbVersion = substr($result['version'], 0, 100);
            
        } catch (Exception $e) {
            $dbStatus = 'error: ' . $e->getMessage();
        }
        
        echo json_encode([
            'status' => 'OK',
            'message' => 'API is running! 🚀',
            'timestamp' => date('Y-m-d H:i:s'),
            'php' => PHP_VERSION,
            'slim' => \Slim\Slim::VERSION,
            'environment' => [
                'app_env' => getenv('APP_ENV') ?: 'unknown',
                'app_debug' => getenv('APP_DEBUG') ?: 'false',
            ],
            'database' => [
                'type' => 'SQL Server',
                'status' => $dbStatus,
                'version' => $dbVersion,
                'host' => getenv('DB_HOST') ?: 'unknown',
                'database' => getenv('DB_DATABASE') ?: 'unknown',
            ]
        ], JSON_PRETTY_PRINT);
    });
    
    // Load auth routes
    require __DIR__ . '/auth.php';
});