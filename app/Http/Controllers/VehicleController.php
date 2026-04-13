<?php

namespace App\Http\Controllers;
/**
 * Public Vehicle Controller
 * 
 * Manages guest-facing inventory display. 
 * Implements strict filtering to ensure only 'published' listings are visible.
 */

use App\Models\Vehicle;
use Inertia\Inertia;
use Inertia\Response;

class VehicleController extends Controller
{
    /**
     * Data Transformation (Internal)
     * 
     * Security: Explicitly defines the fields returned to the frontend.
     * This prevents sensitive internal model data from leaking to the public API.
     */
    private function publishedVehicles()
    {
        return Vehicle::query()
            ->where('is_published', true) // Privacy: Filter out drafts
            ->latest() // UX: Show newest arrivals first
            ->get()
            ->map(fn (Vehicle $vehicle) => [
                'id' => $vehicle->id,
                'title' => $vehicle->title,
                'make' => $vehicle->make,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'price' => (float) $vehicle->price, // Type Casting for JS compatibility
                'mileage' => $vehicle->mileage,
                'fuel_type' => $vehicle->fuel_type,
                'transmission' => $vehicle->transmission,
                'image_path' => $vehicle->image_path,
            ]);
    }

    /**
     * Display the public vehicle gallery.
     */
    public function index(): Response
    {
        return Inertia::render('Vehicles/Index', [
            'vehicles' => $this->publishedVehicles(),
        ]);
    }

    /**
     * Display a specific vehicle listing.
     * 
     * Defensive Check: Even if a user guesses a URL for a draft vehicle, 
     * we abort with a 404 to maintain inventory privacy.
     */
    public function show(Vehicle $vehicle): Response
    {
        abort_unless($vehicle->is_published, 404);

        return Inertia::render('Vehicles/Show', [
            'vehicle' => [
                'id' => $vehicle->id,
                'title' => $vehicle->title,
                'make' => $vehicle->make,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'price' => (float) $vehicle->price,
                'mileage' => $vehicle->mileage,
                'fuel_type' => $vehicle->fuel_type,
                'transmission' => $vehicle->transmission,
                'color' => $vehicle->color,
                'description' => $vehicle->description,
                'image_path' => $vehicle->image_path,
            ],
        ]);
    }
}
