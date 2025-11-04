<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'OK',
    'message' => 'Simple test works!',
    'php_version' => PHP_VERSION,
    'time' => date('Y-m-d H:i:s'),
]);
