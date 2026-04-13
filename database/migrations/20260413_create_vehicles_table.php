<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Vehicles Schema Migration
 * 
 * Defines the database structure for the core inventory system.
 * Optimised for filtering and performance in a Canadian automotive context.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the 'vehicles' table with appropriate data types 
     * and nullability constraints.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('make');
            $table->string('model');
            // Performance: SmallInteger saves space for 4-digit years [1, 3]
            $table->unsignedSmallInteger('year');
            // Financial: Decimal ensures precision for CAD currency [4, 5]
            $table->decimal('price', 10, 2);
            // Metadata: Nullable fields allow for incomplete listings in 'Draft' state
            $table->unsignedInteger('mileage')->nullable();
            $table->string('transmission')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            // Status: Default to published ensures immediate gallery visibility [6]
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
