<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Profile Update Request
 * 
 * Centralizes validation logic for user profile modifications.
 * By using a Form Request, we decouple validation from the controller,
 * adhering to the Single Responsibility Principle.
 */

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Standard length and type constraints to prevent database overflow
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase', // UX: Normalizes data for consistent lookups
                'email',
                'max:255',
                /**
                 * Unique Constraint:
                 * Ensures the email isn't taken by another user, but 
                 * ignores the current user's ID to allow them to save 
                 * without changing their own email.
                 */
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
