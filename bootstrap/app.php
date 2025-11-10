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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'teacher' => \App\Http\Middleware\TeacherMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'upload.size' => \App\Http\Middleware\HandleUploadSize::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Better error handling for authorization exceptions
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'This action is unauthorized.',
                    'error' => $e->getMessage()
                ], 403);
            }
            
            return redirect()->back()->with('error', 'You do not have permission to perform this action. Please contact the administrator if you believe this is an error.');
        });
        
        // Better error handling for authentication exceptions
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            
            return redirect()->route('login')->with('error', 'Please login to continue.');
        });
    })->create();
