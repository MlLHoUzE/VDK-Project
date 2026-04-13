<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Main Database Seeder
 * 
 * The central entry point for populating the database schema.
 * Orchestrates the creation of default administrative accounts 
 * and calls specialized seeders for domain-specific data.
 */
class DatabaseSeeder extends Seeder
{
    // Performance: Disables model events during seeding to speed up the process.
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     * 
     * Sets up the initial state for the Hammond Motors application.
     */
    public function run(): void
    {
        /* 
         * Idempotent Admin Setup: 
         * Ensures a default test account exists without creating 
         * duplicates on subsequent runs of the seeder.
         */
        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'), // Standard dev credential
        ]);

        // Delegating vehicle data generation to its dedicated seeder
        $this->call(VehicleSeeder::class);
    }
}
