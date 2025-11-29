<?php

use App\Http\Middleware\EnsureSecuritySetup;
use App\Http\Middleware\PermissionMiddleware;
use App\Http\Middleware\MultiPermissionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => PermissionMiddleware::class,
            'multi-permission' => MultiPermissionMiddleware::class,
            'security.setup' => EnsureSecuritySetup::class,
        ]);
        
        // Apply security setup check to all authenticated routes
        $middleware->appendToGroup('web', EnsureSecuritySetup::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
