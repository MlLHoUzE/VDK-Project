<?php

/**
 * Service Provider Registration
 * 
 * This file lists the service providers that are loaded on every request 
 * to Hammond Motors. These providers are the 'heart' of the Laravel 
 * bootstrapping process, responsible for binding services into the 
 * container and configuring core framework features.
 */

use App\Providers\AppServiceProvider;

return [
    /**
     * AppServiceProvider: 
     * The primary location for project-specific global configurations, 
     * such as URL schemes, validation rules, and Eloquent settings.
     */
    AppServiceProvider::class,
];
