<?php

namespace App\Exceptions;

use App\Enums\HttpStatus;
use App\Traits\ResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseTrait;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): Response|JsonResponse
    {
        if ($request->is('api/*')) {
            if ($e instanceof ModelNotFoundException) {
                return $this->error(
                    message: 'Entry for ' . str_replace('App\\Models\\', '', $e->getModel()) . ' not found',
                    statusCode: HttpStatus::NOT_FOUND->value
                );
            }

            if ($e instanceof ValidationException) {
                return $this->error(
                    message: $e->getMessage(),
                    errors: $e->errors(),
                    statusCode: HttpStatus::UNPROCESSABLE_ENTITY->value,
                );
            }

            if ($e instanceof AuthenticationException) {
                return $this->error(
                    message: $e->getMessage(),
                    statusCode: HttpStatus::UNAUTHORIZED->value
                );
            }

            if ($e instanceof InternalServerErrorException) {
                return $this->error(
                    message: $e->getMessage(),
                    statusCode: HttpStatus::INTERNAL_SERVER_ERROR->value
                );
            }

            if ($e instanceof InvalidCredentialsException) {
                return $this->error(
                    message: $e->getMessage(),
                    statusCode: HttpStatus::UNAUTHORIZED->value
                );
            }

            return $this->error(
                message: $e->getMessage(),
                statusCode: HttpStatus::INTERNAL_SERVER_ERROR->value
            );
        }

        return parent::render($request, $e);
    }
}
