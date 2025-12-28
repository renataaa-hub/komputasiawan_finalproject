<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\TrackVisitor;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // Tambahkan di route middleware
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'subscription' => \App\Http\Middleware\CheckSubscriptionLimit::class,
            'is_admin'     => \App\Http\Middleware\IsAdmin::class,
            'track.visitor'=> TrackVisitor::class,
        ]);
    })
    
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

    