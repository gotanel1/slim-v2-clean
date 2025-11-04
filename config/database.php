<?php

return [
    'driver' => getenv('DB_CONNECTION') ?: 'sqlsrv',
    'host' => getenv('DB_HOST') ?: 'localhost',
    'port' => getenv('DB_PORT') ?: '1433',
    'database' => getenv('DB_DATABASE') ?: 'service_db',
    'username' => getenv('DB_USERNAME') ?: 'sa',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
];
