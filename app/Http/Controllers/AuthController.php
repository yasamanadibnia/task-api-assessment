<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(protected AuthService $authService)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $userData = $this->authService->login($request->validated());
            if (!$userData) {
                return $this->respondInternalError();
            }
            return $this->respondSuccess('user created successfully', $userData);

        } catch (Exception $exception) {
            return $this->respondInternalError();
        }
    }

    /**
     * Register User
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $userData = $this->authService->register($request->validated());
            return $this->respondSuccess('user created successfully', $userData);

        } catch (Exception $exception) {
            return $this->respondInternalError();
        }
    }
}
