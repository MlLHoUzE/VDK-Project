<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Password Rotation Controller
 * 
 * Manages secure password updates for authenticated users.
 */
class PasswordController extends Controller
{
    /**
     * Update the user's password.
     * 
     * Security Features:
     * - 'current_password': Validates that the user knows their existing 
     *    password before allowing a change (Sudo-mode protection).
     * - 'confirmed': Matches against the 'password_confirmation' field.
     * - Password::defaults(): Applies the project's global complexity requirements.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Persistence: Using 'update' with a one-way Hash for secure storage
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Return back to the profile page with the updated state
        return back();
    }
}
