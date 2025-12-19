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
    public function login(array $credentials): array
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

        return $this->buildAuthResponse($user);
    }

    /**
     * register user and give token.
     */
    public function register(array $data): array
    {
        $user = $this->authRepository->create($data);

        return $this->buildAuthResponse($user);
    }

    /**
     * return auth response data.
     */
    private function buildAuthResponse(User $user): array
    {
        return [
            'user' => $user,
            'token' => $user->createToken('api_token')->plainTextToken,
        ];
    }
}
