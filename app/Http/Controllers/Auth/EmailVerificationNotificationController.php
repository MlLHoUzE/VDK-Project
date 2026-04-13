<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Email Verification Notification Controller
 * 
 * Manages the re-dispatching of verification links.
 * Used when a user hasn't received their initial email or the link has expired.
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     * 
     * Note: This method is protected by 'throttle' middleware in the routes 
     * to prevent spamming the mail server.
     */
    public function store(Request $request): RedirectResponse
    {
        // UX: If the user verified their email in another tab, redirect them immediately.
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        /**
         * Notification Dispatch:
         * Triggers Laravel's internal 'MustVerifyEmail' notification. 
         * This is typically handled by a background queue in production.
         */
        $request->user()->sendEmailVerificationNotification();

        // Feedback: Return to the prompt with a success status for the Vue frontend.
        return back()->with('status', 'verification-link-sent');
    }
}
