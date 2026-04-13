<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

/**
 * Vehicle Seeder
 * 
 * Populates the database with initial sample data.
 * Useful for local development, UI testing, and staging environments.
 */

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Leverages the VehicleFactory to generate realistic, randomized 
     * data in bulk. This ensures the Gallery and Admin layouts can 
     * be tested with a full dataset (including pagination).
     */
    public function run(): void
    {
        // Generates 18 records to provide a full 3x6 grid for Gallery testing
        Vehicle::factory()->count(18)->create();
    }
}
