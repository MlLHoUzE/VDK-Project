<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

/**
 * Application Service Provider
 * 
 * The central location for bootstrapping global application services.
 * Use this class to configure framework-level settings and 
 * performance optimizations.
 */

class AppServiceProvider extends ServiceProvider
{
     /**
     * Register any application services.
     * 
     * Used for binding services into the Service Container.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * 
     * Called after all other service providers have been registered.
     */
    public function boot(): void
    {
        /**
         * Frontend Performance Optimization:
         * Enables Vite prefetching for Inertia links. This proactively
         * downloads page assets in the background, making navigation
         * feel instantaneous for the user.
         */
        Vite::prefetch(concurrency: 3);
    }
}
