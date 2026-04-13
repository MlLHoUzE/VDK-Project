<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Admin Vehicle Controller
 * 
 * Orchestrates the full CRUD lifecycle for the vehicle inventory.
 * Features specialized logic for layout state persistence and 
 * atomic status toggling.
 */
class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * Includes 'view' query parameter handling to maintain the user's 
     * layout preference (List vs. Gallery) across the session.
     */
    public function index(Request $request): Response
    {
        // View State: Defaults to 'list' and validates against allowed types
        $initialView = $request->query('view', 'list');
        if (! in_array($initialView, ['list', 'gallery'], true)) {
            $initialView = 'list';
        }

        // Data Transformation: Prevents over-exposure of internal model attributes
        $vehicles = Vehicle::query()
            ->latest()
            ->get()
            ->map(fn (Vehicle $vehicle) => [
                'id' => $vehicle->id,
                'title' => $vehicle->title,
                'make' => $vehicle->make,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'price' => (float) $vehicle->price, // Type consistency for JS
                'mileage' => $vehicle->mileage,
                'transmission' => $vehicle->transmission,
                'fuel_type' => $vehicle->fuel_type,
                'image_path' => $vehicle->image_path,
                'is_published' => $vehicle->is_published,
            ]);

        return Inertia::render('Admin/Vehicles/Index', [
            'vehicles' => $vehicles,
            'initialView' => $initialView,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Vehicles/Create');
    }

     /**
     * Show a specific vehicle listing.
     * 
     * Captures the 'returnView' to ensure the 'Back' button returns 
     * the user to their previously selected layout.
     */
    public function show(Request $request, Vehicle $vehicle): Response
    {
        $returnView = $request->query('view', 'list');
        if (! in_array($returnView, ['list', 'gallery'], true)) {
            $returnView = 'list';
        }

        return Inertia::render('Admin/Vehicles/Show', [
            'vehicle' => $vehicle,
            'returnView' => $returnView,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Vehicle::create($this->validatedData($request));

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Vehicle created successfully.');
    }

    public function edit(Vehicle $vehicle): Response
    {
        return Inertia::render('Admin/Vehicles/Edit', [
            'vehicle' => $vehicle,
        ]);
    }

     /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $vehicle->update($this->validatedData($request));

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->delete();

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }

    /**
     * Custom Action: Atomic Status Toggle
     * 
     * Allows for rapid visibility updates without requiring the user 
     * to enter the full 'Edit' workflow.
     */
    public function togglePublished(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->update([
            'is_published' => ! $vehicle->is_published,
        ]);

        $message = $vehicle->is_published
            ? 'Vehicle published successfully.'
            : 'Vehicle moved to draft successfully.';

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', $message);
    }

     /**
     * Centralized Validation Rules
     * 
     * Ensures data integrity across both Store and Update operations.
     * Includes range and type constraints tailored for automotive data.
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'make' => ['required', 'string', 'max:120'],
            'model' => ['required', 'string', 'max:120'],
            'year' => ['required', 'integer', 'between:1900,2100'],
            'price' => ['required', 'numeric', 'min:0'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'transmission' => ['nullable', 'string', 'max:100'],
            'fuel_type' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'image_path' => ['nullable', 'url', 'max:2048'],
            'is_published' => ['required', 'boolean'],
        ]);
    }
}
