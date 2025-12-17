<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Response;
use Throwable;

class ApiExceptionHandler
{
    use ApiResponse;

    /**
     * Map of exception classes to their handler methods
     */
    public static array $handlers = [
        AuthenticationException::class => 'handleAuthenticationException',
        AccessDeniedHttpException::class => 'handleAuthenticationException',
        AuthorizationException::class => 'handleAuthorizationException',
        ValidationException::class => 'handleValidationException',
        ModelNotFoundException::class => 'handleNotFoundException',
        NotFoundHttpException::class => 'handleNotFoundException',
        MethodNotAllowedHttpException::class => 'handleMethodNotAllowedException',
        HttpException::class => 'handleHttpException',
        QueryException::class => 'handleQueryException',
    ];

    /**
     * Handle authentication exceptions
     */
    public function handleAuthenticationException(
        AuthenticationException|AccessDeniedHttpException $exception,
        Request                                           $request
    ): JsonResponse
    {
        $this->logException($exception, 'Authentication failed');

        return $this->respondErrorWithCode(Response::HTTP_UNAUTHORIZED, 'Authentication required. Please provide valid credentials.');
    }

    /**
     * Handle authorization exceptions
     */
    public function handleAuthorizationException(
        AuthorizationException $exception,
        Request                $request
    ): JsonResponse
    {
        $this->logException($exception, 'Authorization failed');

        return $this->respondForbidden('You do not have permission to perform this action.');
    }

    /**
     * Handle validation exceptions
     */
    public function handleValidationException(
        ValidationException $exception,
        Request             $request
    ): JsonResponse
    {
        $exceptionErrors = [];

        foreach ($exception->errors() as $field => $messages) {
            foreach ($messages as $message) {
                $exceptionErrors[] = [
                    'field' => $field,
                    'message' => $message,
                ];
            }
        }

        $this->logException($exception, 'Validation failed', ['errors' => $exceptionErrors]);

        return $this->respondValidationError('The provided data is invalid.', $exceptionErrors);
    }

    /**
     * Handle not found exceptions
     */
    public function handleNotFoundException(
        ModelNotFoundException|NotFoundHttpException $exception,
        Request                                      $request
    ): JsonResponse
    {
        $this->logException($exception, 'Resource not found');

        $message = $exception instanceof ModelNotFoundException
            ? 'The requested resource was not found.'
            : "The requested endpoint '{$request->getRequestUri()}' was not found.";

        return $this->respondNotFound($message);
    }

    /**
     * Handle method not allowed exceptions
     */
    public function handleMethodNotAllowedException(
        MethodNotAllowedHttpException $exception,
        Request                       $request
    ): JsonResponse
    {
        $this->logException($exception, 'Method not allowed');

        return $this->respondErrorWithCode(Response::HTTP_METHOD_NOT_ALLOWED, "The {$request->method()} method is not allowed for this endpoint.", [
            'allowed_methods' => $exception->getHeaders()['Allow'] ?? 'Unknown',
        ]);
    }

    /**
     * Handle general HTTP exceptions
     */
    public function handleHttpException(HttpException $exception, Request $request): JsonResponse
    {
        $this->logException($exception, 'HTTP exception occurred');
        return $this->respondInternalError($exception->getMessage() ?: 'An HTTP error occurred.');
    }

    /**
     * Handle database query exceptions
     */
    public function handleQueryException(QueryException $exception, Request $request): JsonResponse
    {
        $this->logException($exception, 'Database query failed', ['sql' => $exception->getSql()]);

        // Handle specific database constraint violations
        $exceptionErrorCode = $exception->errorInfo[1] ?? null;

        switch ($exceptionErrorCode) {
            case 1451: // Foreign key constraint violation
                return $this->respondErrorWithCode(Response::HTTP_CONFLICT, 'Cannot delete this resource because it is referenced by other records.');

            case 1062: // Duplicate entry
                return $this->respondErrorWithCode(Response::HTTP_CONFLICT, 'A record with this information already exists.');

            default:
                return $this->respondInternalError('A database error occurred. Please try again later.');
        }
    }

    /**
     * Log exception with context
     */
    private function logException(Throwable $exception, string $message, array $context = []): void
    {
        if (!env('IS_TEST', true)) {
            Log::log($exception, $message);
        }
    }
}
