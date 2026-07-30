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
        Schema::create('permissions', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            // Contoh: attendance
            $table->string('module', 50);

            // Contoh: create
            $table->string('action', 50);

            // Contoh: ATTENDANCE_CREATE
            $table->string('code', 100)->unique();

            // Contoh: Create Attendance
            $table->string('name', 100);

            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->softDeletes();

            $table->index('module');
            $table->index('action');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};