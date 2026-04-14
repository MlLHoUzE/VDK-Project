<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Email Verification Prompt Controller
 * 
 * Acts as an intermediate 'gate' for unverified users. 
 * If a user attempts to access protected areas without a verified email, 
 * this controller directs them to the verification instructions.
 */
class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     * 
     * Logic:
     * - If already verified: Redirect straight to the management dashboard.
     * - If unverified: Render the VerifyEmail Vue component.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('admin.vehicles.index', absolute: false))
                    : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
