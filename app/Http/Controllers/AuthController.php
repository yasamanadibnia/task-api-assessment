<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(protected AuthService $authService) {}

    /**
     * login user and give token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $userData = $this->authService->login($request->validated());
            if (! $userData) {
                return $this->respondInternalError();
            }

            return $this->respondSuccess(
                data: (new UserResource($userData))->toArray(request())
            );
        } catch (Exception $exception) {
            return $this->respondInternalError();
        }
    }

    /**
     * register user and return token.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $userData = $this->authService->register($request->validated());

            return $this->respondSuccess(
                data: (new UserResource($userData))->toArray(request())
            );
        } catch (Exception $exception) {
            return $this->respondInternalError();
        }
    }
}
