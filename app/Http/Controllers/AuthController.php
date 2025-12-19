<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

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
                return Response::error();
            }

            return Response::success(data: UserResource::make($userData));
        } catch (Exception $exception) {
            return Response::error();
        }
    }

    /**
     * register user and return token.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $userData = $this->authService->register($request->validated());

            return Response::success(data: UserResource::make($userData));
        } catch (Exception $exception) {
            return Response::error();
        }
    }
}
