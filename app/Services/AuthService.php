<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
            ! Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ])
        ) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
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
