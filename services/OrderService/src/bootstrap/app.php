<?php

use App\Http\Middleware\GatewayAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use VladimirYuldashev\LaravelQueueRabbitMQ\LaravelQueueRabbitMQServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(GatewayAuth::class);
        $middleware->alias([
            'gateway.auth' => GatewayAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
