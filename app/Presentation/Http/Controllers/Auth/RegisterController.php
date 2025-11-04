<?php

namespace App\Presentation\Http\Controllers\Auth;

use App\Application\UseCases\Auth\RegisterUseCase;
use App\Application\DTOs\RegisterRequest;
use App\Infrastructure\Http\Validators\RegisterValidator;

class RegisterController
{
    private RegisterUseCase $registerUseCase;
    private RegisterValidator $validator;

    public function __construct(RegisterUseCase $registerUseCase, RegisterValidator $validator)
    {
        $this->registerUseCase = $registerUseCase;
        $this->validator = $validator;
    }

    public function __invoke($request, $response)
    {
        try {
            // Get request body
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
            $registerRequest = RegisterRequest::fromArray($data);
            $user = $this->registerUseCase->execute($registerRequest);

            // Return response
            $response->setStatus(201);
            $response->headers->set('Content-Type', 'application/json');
            echo json_encode([
                'message' => 'Registration successful',
                'user' => $user->toArray()
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
