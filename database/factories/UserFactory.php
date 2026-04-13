<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * User Factory
 * 
 * Generates mock user accounts for testing and seeding.
 * Implements performance optimizations for password hashing to speed up 
 * large-scale database seeding. [6]
 * 
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Cached Hashed Password:
     * Stores the hashed 'password' string so it only needs to be computed once
     * per execution, significantly reducing CPU load during bulk seeding. [6]
     */
    protected static ?string $password;

     /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            // Security: Ensures unique email addresses to avoid database constraint violations.
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Model State: Unverified Email
     * 
     * Allows for testing the 'Verification Gateway' by generating users 
     * who have not yet confirmed their email address. [6]
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
