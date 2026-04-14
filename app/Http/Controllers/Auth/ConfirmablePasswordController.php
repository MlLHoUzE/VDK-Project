<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Confirmable Password Controller
 * 
 * Implements a 'Sudo-Mode' security layer. This requires users to 
 * re-verify their password before accessing high-risk areas 
 * (like profile deletion or password rotation), even if they are 
 * already logged in.
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): Response
    {
        return Inertia::render('Auth/ConfirmPassword');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        // Security: Validates the password against the current authenticated user's email.
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        /**
         * Session Handshake:
         * Stores a timestamp of the confirmation. Laravel's 'password.confirm' 
         * middleware checks this value to determine if a re-challenge is necessary.
         */
        $request->session()->put('auth.password_confirmed_at', time());

        // Redirect: Returns the user to their intended destination (e.g., the Delete Account action).
        return redirect()->intended(route('admin.vehicles.index', absolute: false));
    }
}
