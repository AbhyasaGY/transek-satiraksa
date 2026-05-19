<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Daftarkan alias 'role' untuk RoleMiddleware kita
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        // Pengecualian CSRF untuk rute Webhook
        $middleware->validateCsrfTokens(except: [
            'api/webhook/midtrans',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
