<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Authenticated Session Controller
 * 
 * Manages the user's authentication lifecycle.
 * Handles secure login, session regeneration, and safe logout procedures.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            // Logic: Checks if password recovery is enabled in the routing table
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Validation & Throttling: Handled within the specialized LoginRequest
        $request->authenticate();

        /**
         * 2. Security: Session Regeneration
         * Prevents 'Session Fixation' attacks by refreshing the session ID 
         * immediately upon successful authentication.
         */
        $request->session()->regenerate();

        // 3. Redirection: Sends user to the Admin panel (bypassing dashboard middleman)
        return redirect()->intended(route('admin.vehicles.index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        /**
         * Security: Session Cleanup
         * Invalidate the session and regenerate the CSRF token to ensure 
         * the logged-out state is absolute and secure.
         */
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
