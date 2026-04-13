<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Vehicle Model
 * 
 * Represents a single automotive listing in the Hammond Motors inventory.
 */
class Vehicle extends Model
{
    // Enables the use of VehicleFactory for testing and seeding
    use HasFactory;

     /**
     * Mass Assignment Protection
     * 
     * Defines the attributes that can be safely updated via bulk 
     * operations (e.g., Vehicle::create() or $vehicle->update()).
     */
    protected $fillable = [
        'title',
        'make',
        'model',
        'year',
        'price',
        'mileage',
        'transmission',
        'fuel_type',
        'color',
        'description',
        'image_path',
        'is_published',
    ];

     /**
     * Attribute Casting
     * 
     * Ensures that data types are automatically converted when 
     * retrieved from or saved to the database. This is critical for 
     * maintaining strict types in the Vue frontend.
     */
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            // Financial: Ensures price is always treated as a 2-decimal string
            'price' => 'decimal:2',
            'mileage' => 'integer',
            'is_published' => 'boolean',
        ];
    }
}
