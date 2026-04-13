<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Core User & Authentication Schema
 * 
 * Manages user identities, secure password recovery tokens, 
 * and persistent session state.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Users Table: The central repository for authenticated accounts.
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique(); // Unique constraint prevents duplicate registrations
            $table->timestamp('email_verified_at')->nullable(); // Supports email verification workflow
            $table->string('password'); // Stores hashed credentials
            $table->rememberToken(); // Facilitates 'Remember Me' session persistence
            $table->timestamps();
        });

        // Password Reset Tokens: Short-lived secure tokens for recovery workflows.
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        /* 
         * Sessions Table: 
         * Provides database-backed session storage for high-availability 
         * and detailed user activity tracking.
         */
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index(); // Links session to a specific user
            $table->string('ip_address', 45)->nullable(); // IPv4 or IPv6 support
            $table->text('user_agent')->nullable(); // Stores browser/device metadata
            $table->longText('payload'); // Serialized session data
            $table->integer('last_activity')->index(); // Indexed for efficient session garbage collection
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
