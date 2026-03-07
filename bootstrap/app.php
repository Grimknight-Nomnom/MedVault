<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Your middleware configurations go here (if any)
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        // --- CATCH 419 SESSION EXPIRED (CSRF TOKEN MISMATCH) ---
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            
            // Redirect back, keep what they typed (except password), and show your custom error
            return redirect()->back()
                ->withInput($request->except('password'))
                ->withErrors([
                    'login_identifier' => 'Your session expired because you were inactive for too long. Please try again.'
                ]);
        });
        
    })->create();