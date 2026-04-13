<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Login Request
 * 
 * Specialized Form Request for handling authentication attempts.
 * Includes built-in protection against brute-force attacks via 
 * sophisticated rate limiting and IP-based throttling.
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Authentication Workflow
     * 
     * Orchestrates the security check:
     * 1. Verifies rate limits haven't been exceeded.
     * 2. Attempts to match credentials.
     * 3. Increments failure counts or clears success states.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Track failed attempt to trigger throttling
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        // Reset limiter upon successful authentication 
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Rate Limiting Logic (Throttling)
     * 
     * Limits users to 5 login attempts per minute. If exceeded, 
     * dispatches a 'Lockout' event and throws a validation error 
     * containing the remaining cooldown time.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Unique Throttle Identifier
     * 
     * Generates a key based on the normalized email and client IP address
     * to prevent attackers from bypassing limits by switching accounts or IPs.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
