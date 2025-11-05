<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'status' => 'ok',
    'message' => 'PHP is working!',
    'php_version' => PHP_VERSION,
    'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
    'script_name' => $_SERVER['SCRIPT_NAME'] ?? 'Unknown',
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
    'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'Unknown',
    'timestamp' => date('Y-m-d H:i:s'),
    'checks' => [
        'mod_rewrite' => function_exists('apache_get_modules') ? in_array('mod_rewrite', apache_get_modules()) : 'Cannot check',
        'env_loaded' => file_exists(__DIR__ . '/../.env') ? 'Yes' : 'No',
        'vendor_exists' => file_exists(__DIR__ . '/../vendor/autoload.php') ? 'Yes' : 'No',
    ]
], JSON_PRETTY_PRINT);