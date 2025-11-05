<?php

return [
    'jwt' => [
        'secret' => $_ENV['JWT_SECRET'] ?? 'TRF-FeedMill-Super-Secret-Key-2024-Production-abc123xyz789',
        'expiration' => (int) ($_ENV['JWT_EXPIRATION'] ?? 3600), // 1 hour
        'refresh_expiration' => (int) ($_ENV['JWT_REFRESH_EXPIRATION'] ?? 604800), // 7 days
    ],
];
