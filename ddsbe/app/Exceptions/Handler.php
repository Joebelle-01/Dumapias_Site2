<?php
namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Throwable $exception)
    {
        // HTTP Not Found (404)
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'error' => 'URL not found',
                'code' => 404
            ], 404);
        }

        // Method Not Allowed (405)
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'error' => 'Method not allowed',
                'code' => 405
            ], 405);
        }

        // Model Not Found (e.g., User not found)
        if ($exception instanceof ModelNotFoundException) {
            $modelName = class_basename($exception->getModel());
            return response()->json([
                'error' => "{$modelName} not found with the given ID",
                'code' => 404
            ], 404);
        }

        // Validation Exception (422)
        if ($exception instanceof ValidationException) {
            $errors = $exception->validator->errors()->getMessages();
            return response()->json([
                'error' => 'Validation failed',
                'details' => $errors,
                'code' => 422
            ], 422);
        }

        // Authorization Exception (403)
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'error' => $exception->getMessage() ?: 'Forbidden',
                'code' => 403
            ], 403);
        }

        // HTTP Exceptions (other HTTP errors)
        if ($exception instanceof HttpException) {
            return response()->json([
                'error' => $exception->getMessage() ?: 'HTTP Error',
                'code' => $exception->getStatusCode()
            ], $exception->getStatusCode());
        }

        // If in debug mode, return the default error (for development)
        if (env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }

        // For any other exception in production
        return response()->json([
            'error' => 'Unexpected error occurred',
            'code' => 500
        ], 500);
    }
}