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
    // WARNING: ¿Por qué aquí? Por qué en este archivo es donde Laravel gestiona y ejecuta los servicios de Seguridad.
    // NOTE: Se ha añadido un alias 'admin' para registrar un filtro exclusivo a los administradores.
    ->withMiddleware(function (Middleware $middleware): void {
        // ** Le añadimos un nombre más corto a la dirección, ese nombre llamado middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
