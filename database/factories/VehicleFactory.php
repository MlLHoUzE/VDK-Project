<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Vehicle Factory
 * 
 * Generates realistic sample data for the Vehicle model.
 * Used primarily for database seeding and automated testing suites.
 * 
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     * 
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 1. Data Preparation: Local variables used to build a coherent 'title' string.
        $make = fake()->randomElement(['Toyota', 'Honda', 'Ford', 'BMW', 'Audi', 'Nissan', 'Kia']);
        $model = fake()->randomElement(['Corolla', 'Civic', 'Mustang', 'X5', 'A4', 'Altima', 'Sportage']);
        $year = fake()->numberBetween(2014, 2025);
        $price = fake()->numberBetween(12000, 68000);
        $mileage = fake()->numberBetween(5000, 145000);

        return [
            // Semantic Title: Derived from other attributes for realistic inventory display.
            'title' => "{$year} {$make} {$model}",
            'make' => $make,
            'model' => $model,
            'year' => $year,
            'price' => $price,
            'mileage' => $mileage,
            'transmission' => fake()->randomElement(['Automatic', 'Manual', 'CVT']),
            'fuel_type' => fake()->randomElement(['Gasoline', 'Hybrid', 'Diesel', 'Electric']),
            'color' => fake()->safeColorName(),
            'description' => fake()->paragraph(3),
            // Placeholder: High-quality Unsplash image ensures the Gallery looks professional during dev.
            'image_path' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1200&q=80',
            /* 
             * Business Logic Edge Case: 
             * 85% probability of being published. This allows developers to test 
             * the 'Draft' vs 'Public' filtering logic without manual database edits.
             */
            'is_published' => fake()->boolean(85),
        ];
    }
}
