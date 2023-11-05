<?php

namespace App\Exceptions;


use HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [ //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [ //
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
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {

        });
    }


    public function render($request, Throwable $e): \Illuminate\Http\Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($e instanceof UnauthorizedException) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'User does not have the right permissions.'
                ],
                403
            );
        }

        if ($e instanceof ModelNotFoundException) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'No query results for model.'
                ],
                404
            );
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'Method not support for the route.'
                ],
                405
            );
        }

        if ($e instanceof NotFoundHttpException) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'The specified URL cannot be found.'
                ],
                404
            );
        }

        if ($e instanceof HttpException) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                422
            );
        }

        if ($e instanceof QueryException) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                422
            );
        }

        return parent::render($request, $e);
    }
}
