<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

         /**
         * The Password Broker:
         * Laravel's internal service generates the token and dispatches 
         * the notification. This ensures the security logic is isolated 
         * from the web controller.
         */
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Success: The token was successfully emailed
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        /**
         * Error Handling:
         * If the broker fails (e.g., user not found), we throw a 
         * ValidationException which Inertia automatically catches and 
         * maps back to the frontend form.
         */
        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
