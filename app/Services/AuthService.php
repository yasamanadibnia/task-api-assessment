<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Exception;

class AuthService
{
    public function __construct(
        private AuthRepository $authRepository
    ) {}

    /**
     * find by email user and give token.
     *
     * @throws Exception
     */
    public function login(array $credentials): User
    {
        if (
            ! auth()->attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ])
        ) {
            throw new Exception('Invalid email or password'); // no backslash needed
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
