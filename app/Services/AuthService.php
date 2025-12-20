<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Validation\ValidationException;
use Mockery\Exception;

class AuthService
{
    public function __construct(
        private AuthRepository $authRepository
    ) {}

    /**
     * find by email user and give token.
     *
     * @throws ValidationException
     */
    public function login(array $credentials): User
    {
        if (
            ! auth()->attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ])
        ) {
            throw new Exception(
                response: response()->json([
                    'message' => 'Invalid credentials',
                    'errors' => ['email' => ['Invalid email or password']],
                ], 422)
            );
        }

        $user = $this->authRepository->findByEmail($credentials['email']);

        return $user->setAttribute('token', $user->createToken('api_token')->plainTextToken);
    }

    /**
     * register user and give token.
     */
    public function register(array $data): User
    {
        $user = $this->authRepository->create($data);

        return $user->setAttribute('token', $user->createToken('api_token')->plainTextToken);
    }
}
