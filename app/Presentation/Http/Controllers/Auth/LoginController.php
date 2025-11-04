<?php

namespace App\Presentation\Http\Controllers\Auth;

use App\Application\UseCases\Auth\LoginUseCase;
use App\Application\DTOs\LoginRequest;
use App\Infrastructure\Http\Validators\LoginValidator;

class LoginController
{
    private LoginUseCase $loginUseCase;
    private LoginValidator $validator;

    public function __construct(LoginUseCase $loginUseCase, LoginValidator $validator)
    {
        $this->loginUseCase = $loginUseCase;
        $this->validator = $validator;
    }

    public function __invoke($request, $response)
    {
        try {
            // Get request body (Slim 2.x style)
            $data = json_decode($request->getBody(), true);
            
            if (!$data) {
                $response->setStatus(400);
                $response->headers->set('Content-Type', 'application/json');
                echo json_encode(['error' => 'Invalid JSON']);
                return;
            }

            // Validate input
            $errors = $this->validator->validate($data);
            if (!empty($errors)) {
                $response->setStatus(422);
                $response->headers->set('Content-Type', 'application/json');
                echo json_encode(['errors' => $errors]);
                return;
            }

            // Execute use case
            $loginRequest = LoginRequest::fromArray($data);
            $result = $this->loginUseCase->execute($loginRequest);

            // Return response
            $response->setStatus(200);
            $response->headers->set('Content-Type', 'application/json');
            echo json_encode([
                'message' => 'Login successful',
                'user' => $result['user']->toArray(),
                'token' => $result['token']
            ]);

        } catch (\DomainException $e) {
            $response->setStatus(400);
            $response->headers->set('Content-Type', 'application/json');
            echo json_encode(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            $response->setStatus(500);
            $response->headers->set('Content-Type', 'application/json');
            echo json_encode([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
