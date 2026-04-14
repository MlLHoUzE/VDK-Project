<?php

use App\Models\User;
use App\Models\Vehicle;
use Inertia\Testing\AssertableInertia as Assert;

/**
 * Vehicle Management Feature Tests
 * 
 * Verifies that only authenticated admins can manage inventory 
 * and that data is persisted/transformed correctly.
 */

test('guests are redirected away from the admin inventory', function () {
    $this->get(route('admin.vehicles.index'))
        ->assertRedirect(route('login'));
});

test('admin can see the vehicle list and toggle between views', function () {
    $admin = User::factory()->create();
    Vehicle::factory()->count(5)->create();

    $this->actingAs($admin)
        ->get(route('admin.vehicles.index', ['view' => 'gallery']))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Vehicles/Index')
            ->has('vehicles', 5)
            ->where('initialView', 'gallery')
        );
});

test('admin can create a vehicle with valid data', function () {
    $admin = User::factory()->create();
    
    $payload = [
        'title' => '2025 Ford Mustang GT',
        'make' => 'Ford',
        'model' => 'Mustang',
        'year' => 2025,
        'price' => 55000,
        'is_published' => true,
    ];

    $this->actingAs($admin)
        ->post(route('admin.vehicles.store'), $payload)
        ->assertRedirect(route('admin.vehicles.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('vehicles', ['title' => '2025 Ford Mustang GT']);
});

test('admin can toggle a vehicle to draft status', function () {
    $admin = User::factory()->create();
    $vehicle = Vehicle::factory()->create(['is_published' => true]);

    $this->actingAs($admin)
        ->patch(route('admin.vehicles.toggle-published', $vehicle))
        ->assertRedirect();

    expect($vehicle->fresh()->is_published)->toBeFalse();
});

test('public gallery only displays published vehicles', function () {
    Vehicle::factory()->create(['title' => 'Public Car', 'is_published' => true]);
    Vehicle::factory()->create(['title' => 'Secret Car', 'is_published' => false]);

    $response = $this->get(route('vehicles.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->has('vehicles', 1)
        ->where('vehicles.0.title', 'Public Car')
    );
});
