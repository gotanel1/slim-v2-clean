<?php

// Auth Group
$app->group('/auth', function () use ($app) {
    
    // Test Endpoint
    $app->get('/test', function () use ($app) {
        echo json_encode([
            'status' => 'OK',
            'message' => '✅ Auth endpoints are working!',
            'database' => 'SQL Server 2008 R2',
            'getenv_test' => [
                'DB_HOST' => getenv('DB_HOST') ?: 'not found',
                'APP_ENV' => getenv('APP_ENV') ?: 'not found',
            ],
            'endpoints' => [
                'register' => BASE_PATH . '/api/auth/register (POST)',
                'login' => BASE_PATH . '/api/auth/login (POST)',
                'profile' => BASE_PATH . '/api/auth/me (GET)',
            ],
            'test_data' => [
                'register' => [
                    'email' => 'test@trf.com',
                    'password' => 'password123',
                    'name' => 'Test User'
                ],
                'login' => [
                    'email' => 'test@trf.com',
                    'password' => 'password123'
                ]
            ]
        ], JSON_PRETTY_PRINT);
    });
    
    // Register
    $app->post('/register', function () use ($app) {
        try {
            $data = json_decode($app->request->getBody(), true);
            
            if (!$data) {
                $app->response->setStatus(400);
                echo json_encode(['error' => 'Invalid JSON']);
                return;
            }
            
            $validator = $app->container['RegisterValidator'];
            $errors = $validator->validate($data);
            if (!empty($errors)) {
                $app->response->setStatus(422);
                echo json_encode(['errors' => $errors]);
                return;
            }
            
            $userRepo = $app->container['UserRepository'];
            if ($userRepo->findByEmail($data['email'])) {
                $app->response->setStatus(400);
                echo json_encode(['error' => 'Email already exists']);
                return;
            }
            
            $passwordHasher = $app->container['PasswordHasher'];
            $user = new \App\Domain\Entities\User(
                $data['email'],
                $passwordHasher->hash($data['password']),
                $data['name']
            );
            
            $savedUser = $userRepo->save($user);
            
            $app->response->setStatus(201);
            echo json_encode([
                'message' => 'Registration successful',
                'user' => $savedUser->toArray()
            ], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            $app->response->setStatus(500);
            echo json_encode([
                'error' => 'Registration failed',
                'message' => $e->getMessage(),
                'trace' => getenv('APP_DEBUG') === 'true' ? $e->getTraceAsString() : null
            ]);
        }
    });
    
    // Login
    $app->post('/login', function () use ($app) {
        try {
            $data = json_decode($app->request->getBody(), true);
            
            if (!$data) {
                $app->response->setStatus(400);
                echo json_encode(['error' => 'Invalid JSON']);
                return;
            }
            
            $validator = $app->container['LoginValidator'];
            $errors = $validator->validate($data);
            if (!empty($errors)) {
                $app->response->setStatus(422);
                echo json_encode(['errors' => $errors]);
                return;
            }
            
            $userRepo = $app->container['UserRepository'];
            $user = $userRepo->findByEmail($data['email']);
            
            if (!$user) {
                $app->response->setStatus(401);
                echo json_encode(['error' => 'Invalid credentials']);
                return;
            }
            
            $passwordHasher = $app->container['PasswordHasher'];
            if (!$passwordHasher->verify($data['password'], $user->getPassword())) {
                $app->response->setStatus(401);
                echo json_encode(['error' => 'Invalid credentials']);
                return;
            }
            
            $tokenService = $app->container['JwtTokenService'];
            $token = $tokenService->generate($user);
            
            echo json_encode([
                'message' => 'Login successful',
                'user' => $user->toArray(),
                'token' => $token,
                'expires_in' => (int) getenv('JWT_EXPIRATION') ?: 3600
            ], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            $app->response->setStatus(500);
            echo json_encode([
                'error' => 'Login failed',
                'message' => $e->getMessage(),
                'trace' => getenv('APP_DEBUG') === 'true' ? $e->getTraceAsString() : null
            ]);
        }
    });
    
    // Get Profile (Protected)
    $app->get('/me', function () use ($app) {
        try {
            $authHeader = $app->request->headers->get('Authorization');
            
            if (!$authHeader) {
                $app->response->setStatus(401);
                echo json_encode(['error' => 'No token provided']);
                return;
            }
            
            $token = str_replace('Bearer ', '', $authHeader);
            $tokenService = $app->container['JwtTokenService'];
            $decoded = $tokenService->decode($token);
            
            $userRepo = $app->container['UserRepository'];
            $user = $userRepo->findById($decoded->sub);
            
            if (!$user) {
                $app->response->setStatus(404);
                echo json_encode(['error' => 'User not found']);
                return;
            }
            
            echo json_encode([
                'user' => $user->toArray(),
                'token_info' => [
                    'issued_at' => date('Y-m-d H:i:s', $decoded->iat),
                    'expires_at' => date('Y-m-d H:i:s', $decoded->exp),
                ]
            ], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            $app->response->setStatus(401);
            echo json_encode([
                'error' => 'Invalid token',
                'message' => $e->getMessage()
            ]);
        }
    });
});