<?php

use App\Http\Middleware\EnsureIsKasir;
use App\Http\Middleware\EnsureIsOwner;
use App\Http\Middleware\EnsureWarungSetup;
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
        /**
         * Daftarkan alias middleware agar bisa dipakai di routes/web.php
         * seperti: Route::middleware(['auth', 'owner', 'warung.setup'])
         */
        $middleware->alias([
            'owner'        => EnsureIsOwner::class,
            'kasir'        => EnsureIsKasir::class,
            'warung.setup' => EnsureWarungSetup::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
