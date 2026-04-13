<?php

namespace App\Http\Middleware;

/**
 * Inertia Request Handler
 * 
 * This middleware manages the data synchronization between Laravel and Vue.
 * It defines which data is globally available to every Vue component 
 * without needing to pass it explicitly from every controller method.
 */

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Root Template
     * Points to the 'app.blade.php' file that bootstraps the SPA.
     */
    protected $rootView = 'app';

   /**
     * Asset Versioning
     * Helps Inertia detect when the frontend build has changed to 
     * force a hard page reload for the user.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

     /**
     * Global Shared Props
     * 
     * Data defined here is automatically merged into the '$page.props' 
     * object in every Vue component.
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            // Authentication: Globally shares the current user object
            'auth' => [
                'user' => $request->user(),
            ],
            // Flash Messaging: Automatically passes session alerts to the frontend
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
            ],
        ];
    }
}
