<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Password Reset Execution Controller
 * 
 * Finalizes the "Forgot Password" workflow by validating the unique 
 * reset token and persisting the new hashed credentials.
 */
class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     * 
     * Passes the email and token from the URL to the Vue component 
     * to hydrate the form.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

         /**
         * The Password Reset Broker:
         * Validates the token against the 'password_reset_tokens' table.
         * If valid, it executes the callback to update the user record.
         */
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                // Security: Updates password and rotates the remember_token 
                // to invalidate any existing persistent sessions.
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // Success: Redirect to login with a localized status message
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        // Error: Map broker failure (e.g., expired token) back to the email field
        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
