<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ApiExceptionHandler
{
    use ApiResponse;

    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    /**
     * Exception → handler map
     * (kept minimal to reduce coupling)
     */
    public static array $handlers = [
        AuthenticationException::class => 'handleAuthenticationException',
        AuthorizationException::class => 'handleAuthorizationException',
        ValidationException::class => 'handleValidationException',
        ModelNotFoundException::class => 'handleNotFoundException',
        HttpException::class => 'handleHttpException',
        QueryException::class => 'handleQueryException',
    ];

    /**
     * Handle authentication exceptions
     */
    public function handleAuthenticationException(
        AuthenticationException $exception
    ): JsonResponse {
        $this->logException($exception, 'Authentication failed');

        return $this->respondErrorWithCode(
            Response::HTTP_UNAUTHORIZED,
            'Authentication required. Please provide valid credentials.'
        );
    }

    /**
     * Handle authorization exceptions
     */
    public function handleAuthorizationException(
        AuthorizationException $exception
    ): JsonResponse {
        $this->logException($exception, 'Authorization failed');

        return $this->respondForbidden(
            'You do not have permission to perform this action.'
        );
    }

    /**
     * Handle validation exceptions
     */
    public function handleValidationException(
        ValidationException $exception
    ): JsonResponse {
        $errors = [];

        foreach ($exception->errors() as $field => $messages) {
            foreach ($messages as $message) {
                $errors[] = [
                    'field' => $field,
                    'message' => $message,
                ];
            }
        }

        $this->logException($exception, 'Validation failed', [
            'errors' => $errors,
        ]);

        return $this->respondValidationError(
            'The provided data is invalid.',
            $errors
        );
    }

    /**
     * Handle not found exceptions
     */
    public function handleNotFoundException(
        ModelNotFoundException $exception
    ): JsonResponse {
        $this->logException($exception, 'Resource not found');

        return $this->respondNotFound(
            'The requested resource was not found.'
        );
    }

    /**
     * Handle HTTP exceptions
     */
    public function handleHttpException(
        HttpException $exception
    ): JsonResponse {
        $this->logException($exception, 'HTTP exception occurred');

        return $this->respondErrorWithCode(
            $exception->getStatusCode(),
            $exception->getMessage() ?: 'An HTTP error occurred.'
        );
    }

    /**
     * Handle database query exceptions
     */
    public function handleQueryException(
        QueryException $exception
    ): JsonResponse {
        $this->logException($exception, 'Database query failed', [
            'sql' => $exception->getSql(),
        ]);

        $errorCode = $exception->errorInfo[1] ?? null;

        return match ($errorCode) {
            1451 => $this->respondErrorWithCode(
                Response::HTTP_CONFLICT,
                'Cannot delete this resource because it is referenced by other records.'
            ),
            1062 => $this->respondErrorWithCode(
                Response::HTTP_CONFLICT,
                'A record with this information already exists.'
            ),
            default => $this->respondInternalError(
                'A database error occurred. Please try again later.'
            ),
        };
    }

    /**
     * Log exception with context
     */
    private function logException(
        Throwable $exception,
        string $message,
        array $context = []
    ): void {
        $this->logger->error($message, array_merge(
            $context,
            ['exception' => $exception]
        ));
    }
}
