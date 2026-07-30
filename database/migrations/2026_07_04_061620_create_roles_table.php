<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {

            // Primary Key
            $table->id();

            // Public Identifier
            $table->uuid('uuid')->unique();

            // Role Code
            $table->string('code', 30)->unique();

            // Role Name
            $table->string('name', 100);

            // Description
            $table->text('description')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // Laravel Timestamp
            $table->timestamps();

            // Soft Delete
            $table->softDeletes();

            // Index
            $table->index('name');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};