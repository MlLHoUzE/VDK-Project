<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cache Infrastructure Migration
 * 
 * Provides database-backed storage for application caching and atomic locks.
 * Using a cache layer significantly improves performance by reducing 
 * expensive database queries and computations.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cache Table: Stores serialized data with a unique key for rapid retrieval.
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->bigInteger('expiration')->index();
        });

        /* 
         * Cache Locks: 
         * Provides atomic locking mechanisms to prevent 'race conditions'.
         * Ensures that specific tasks (like generating a report) are only 
         * executed by one process at a time.
        */
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->bigInteger('expiration')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
