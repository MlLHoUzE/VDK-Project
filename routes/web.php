<?php

/**
 * Web Routes
 * 
 * Defines the public and administrative routes for Hammond Motors.
 * - Public: Gallery and Detailed vehicle specs.
 * - Private (Admin): CRUD management and published status toggling.
 */
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

// --- Public Inventory Routes ---
Route::get('/', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

// --- Authenticated Admin & Profile Routes ---
Route::middleware('auth')->group(function () {
    // Admin Vehicle Management
    Route::prefix('admin')->name('admin.')->group(function () {
        // Custom Action: Direct endpoint for toggling visibility without a full update
        Route::patch('vehicles/{vehicle}/toggle-published', [AdminVehicleController::class, 'togglePublished'])
            ->name('vehicles.toggle-published');
            // Resourceful Routes: Handles Index, Create, Store, Edit, Update, Destroy
        Route::resource('vehicles', AdminVehicleController::class)->except(['show']);
        /* 
         * Important: Specific parameterized routes must follow resource routes 
         * to avoid matching static paths (like 'create') as IDs.
         */
        Route::get('vehicles/{vehicle}', [AdminVehicleController::class, 'show'])->name('vehicles.show');
    });

    // User Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Load standard Laravel Breeze/Auth scaffolding routes
require __DIR__.'/auth.php';
