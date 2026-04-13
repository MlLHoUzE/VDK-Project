<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Mass Assignment: Safely populates the model with validated data only
        $request->user()->fill($request->validated());

        /**
         * Security: If the email is changed, we must invalidate the 
         * 'verified' status to force a new verification handshake.
         */
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

   /**
     * Delete the user's account.
     * 
     * Security: Requires password confirmation (Sudo-mode) before 
     * performing permanent data destruction.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Step 1: Log the user out to kill the active session
        Auth::logout();

        // Step 2: Remove the record from the database
        $user->delete();

        /**
         * Step 3: Session Security
         * Invalidates the current session and regenerates the CSRF token 
         * to prevent session fixation attacks after account removal.
         */
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
