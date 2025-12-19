<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        MessageException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (MessageException $e, $request) {
            $code = $e->getCode() ?: ResponseCode::HTTP_UNPROCESSABLE_ENTITY;
            $message = $e->getMessage() ?: trans('messages.something_went_wrong');

            return Response::error(
                message: $message,
                code: $code,
            );
        });

        $this->renderable(function (ValidationException $e, $request) {
            return Response::error(
                message: trans('messages.validation_failed'),
                data: $e->errors(),
                code: $e->status,
            );
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            return Response::error(
                message: trans('messages.unauthenticated'),
                code: ResponseCode::HTTP_UNAUTHORIZED,
            );
        });

        $this->renderable(function (UnauthorizedException $e, $request) {
            return Response::error(
                message: trans('messages.unauthorized'),
                code: ResponseCode::HTTP_FORBIDDEN,
            );
        });

        $this->renderable(function (RouteNotFoundException $e, $request) {
            return Response::error(
                message: trans('messages.route_not_found') ?: 'Route not found',
                code: ResponseCode::HTTP_NOT_FOUND,
            );
        });

        $this->renderable(function (ThrottleRequestsException $e, $request) {
            return Response::error(
                message: trans('messages.too_many_attempts') ?: 'Too Many Attempts.',
                code: ResponseCode::HTTP_TOO_MANY_REQUESTS,
            );
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            return Response::error(
                message: $e->getMessage(),
                code: ResponseCode::HTTP_BAD_REQUEST,
            );
        }

        return parent::render($request, $e);
    }
}
