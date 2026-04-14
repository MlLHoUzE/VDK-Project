<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * Email Verification Handler
 * 
 * Processes the final step of the email verification handshake.
 * This controller is invoked via a 'Signed URL' sent to the user's email,
 * ensuring the request is authentic and hasn't been tampered with.
 */
class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // 1. Idempotency Check: If already verified, redirect immediately to the admin panel.
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('admin.vehicles.index', absolute: false).'?verified=1');
        }

        // 2. State Update: Persist the verification timestamp to the database.
        if ($request->user()->markEmailAsVerified()) {
            /**
             * Event Dispatch:
             * Signals the rest of the system (e.g., welcome emails or analytics)
             * that a new user has successfully verified their identity.
             */
            event(new Verified($request->user()));
        }

        // 3. Final Redirect: Send user to the Admin Vehicle Dashboard.
        return redirect()->intended(route('admin.vehicles.index', absolute: false).'?verified=1');
    }
}
