<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Support\Response;


use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if (!$request->is('api/*')) {
                return null;
            }

            if ($e instanceof AuthenticationException) {
                return Response::error(401, "Unauthenticated", isFriendly: true);
            }

            if ($e instanceof AuthorizationException) {
                return Response::error(403, 'This action is unauthorized', isFriendly: true);
            }

            if ($e instanceof ValidationException) {
                return Response::error(422, $e->getMessage(), $e->errors(), true);
            }

            if ($e instanceof HttpException) {
                return Response::error($e->getStatusCode(), $e->getMessage() ?? 'Request error', isFriendly: true);
            }

            return Response::error(500, 'Internal server error');

        });
    })->create();
