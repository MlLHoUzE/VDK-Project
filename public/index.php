<?php

/**
 * Public Entry Point (index.php)
 * 
 * This file is the first point of contact for all requests entering 
 * the application. It bootstraps the framework and hands off 
 * the request to the HTTP Kernel.
 */
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Benchmarking: Captures the exact start time for performance monitoring.
define('LARAVEL_START', microtime(true));

// Maintenance Mode: Checks if the site is intentionally 'down' for updates.
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoloader: Registers Composer's autoloader to manage dependencies and class mapping.
require __DIR__.'/../vendor/autoload.php';

/** 
 * Bootstrap: 
 * Loads the application instance and configurations from the bootstrap directory.
 * @var Application $app 
 */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Execution: Captures the incoming HTTP request and dispatches it through the router.
$app->handleRequest(Request::capture());
