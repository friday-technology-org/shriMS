<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('cms.login'));
        $middleware->append(\App\Http\Middleware\XssSanitizer::class);
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                if ($e instanceof \Illuminate\Auth\AuthenticationException || 
                    $e instanceof \Illuminate\Validation\ValidationException ||
                    $e instanceof \Illuminate\Http\Exceptions\HttpResponseException) {
                    return; // Let Laravel handle redirects for these exceptions
                }

                $status = $e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface ? $e->getStatusCode() : 500;
                
                // For CSRF Token Mismatch
                if ($e instanceof \Illuminate\Session\TokenMismatchException) {
                    $status = 419;
                }

                if (view()->exists("cms-core::errors.{$status}")) {
                    return response()->view("cms-core::errors.{$status}", ['exception' => $e], $status);
                }
            }
        });
    })->create();
