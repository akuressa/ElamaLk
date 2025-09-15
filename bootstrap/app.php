<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'Installer' => \App\Http\Middleware\Installer::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'setLocale' => \App\Http\Middleware\SetLocale::class,
            'business' => \App\Http\Middleware\BusinessMiddleware::class,
            'business-admin' => \App\Http\Middleware\BusinessAdminMiddleware::class,
            'checkType' => \App\Http\Middleware\CheckType::class,
            'scriptsanitizer' => \App\Http\Middleware\ScriptSanitizer::class,
            'user' => \App\Http\Middleware\UserMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
