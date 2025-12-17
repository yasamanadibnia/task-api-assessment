<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct(
        private readonly AuthRepository $authRepository
    ) {
    }

    public function login(array $credentials): array
    {
        if (
            !Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            ])
        ) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        $user = $this->authRepository->findByEmail($credentials['email']);

        return $this->buildAuthResponse($user);
    }

    public function register(array $data): array
    {
        $user = $this->authRepository->create($data);

        return $this->buildAuthResponse($user);
    }

    private function buildAuthResponse($user): array
    {
        return [
            'user' => $user,
            'token' => $user->createToken('api_token')->plainTextToken,
        ];
    }
}
