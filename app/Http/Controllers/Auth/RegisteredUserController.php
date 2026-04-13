<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * User Registration Controller
 * 
 * Orchestrates the creation of new administrative accounts.
 * Handles validation, password security, and automatic session initialization
 * upon successful registration.
 */
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        /**
         * Validation & Normalization:
         * - 'lowercase': Prevents duplicate identities via casing variations.
         * - 'confirmed': Requires the password_confirmation field to match.
         * - Rules\Password: Adheres to the global security defaults defined in AppServiceProvider.
         */
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        // Persistence: Creating the user with a one-way hashed password.       
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        /**
         * Post-Registration Hooks:
         * Dispatches the 'Registered' event to trigger background tasks
         * such as sending the verification email.
         */
        event(new Registered($user));

        // Session Initialization: Automatically logs the user in to streamline onboarding.
        Auth::login($user);

        // Redirect: Directly to the management dashboard.
        return redirect(route('admin.vehicles.index', absolute: false));
    }
}
