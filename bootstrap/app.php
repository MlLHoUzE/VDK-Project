<?php

/**
 * Laravel 11 Application Bootstrapper
 * 
 * This file serves as the unified configuration point for:
 * - Routing (Web, Console, and Health Checks)
 * - Middleware Stacks
 * - Exception Handling
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    // 1. Routing Configuration
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up', // System Health Monitoring endpoint
    )
    // 2. Middleware Configuration
    ->withMiddleware(function (Middleware $middleware): void {
        // Appending Global Web Middleware
        $middleware->web(append: [
            // Synchronizes Laravel data with the Vue frontend via Inertia
            \App\Http\Middleware\HandleInertiaRequests::class,
            // Performance: Enhances LCP by preloading essential assets via Link
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    // 3. Exception Handling
    ->withExceptions(function (Exceptions $exceptions): void {
        // Custom exception reporting logic goes here
    })->create();
