<?php

/**
 * Console Routes
 * 
 * This file defines custom Artisan commands for the application CLI.
 * Commands defined here are registered automatically and can be 
 * executed via 'php artisan [command-name]'.
 */
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Example Command: Used to verify CLI functionality and provide a simple 'Inspiring' quote.
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
