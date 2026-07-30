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
        Schema::create('assignments', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            /*
            |--------------------------------------------------------------------------
            | Assignment
            |--------------------------------------------------------------------------
            */

            $table->string('assignment_number', 30)->unique();

            $table->string('title', 150);

            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Office
            |--------------------------------------------------------------------------
            */

            $table->foreignId('office_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Assignment Location
            |--------------------------------------------------------------------------
            */

            $table->string('location_name', 150);

            $table->text('address')->nullable();

            $table->decimal('latitude', 10, 7);

            $table->decimal('longitude', 10, 7);

            $table->integer('radius')->default(200);

            /*
            |--------------------------------------------------------------------------
            | Priority
            |--------------------------------------------------------------------------
            */

            $table->enum('priority', [
                'Low',
                'Medium',
                'High',
                'Critical',
            ])->default('Medium');

            /*
            |--------------------------------------------------------------------------
            | Assignment Type
            |--------------------------------------------------------------------------
            */

            $table->enum('assignment_type', [

                'Maintenance',

                'Installation',

                'Inspection',

                'Survey',

                'Emergency',

            ]);

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [

                'Draft',

                'Assigned',

                'In Progress',

                'Completed',

                'Cancelled',

            ])->default('Draft');

            /*
            |--------------------------------------------------------------------------
            | Schedule
            |--------------------------------------------------------------------------
            */

            $table->dateTime('start_datetime');

            $table->dateTime('end_datetime');

            /*
            |--------------------------------------------------------------------------
            | Created By
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('assignment_number');

            $table->index('office_id');

            $table->index('priority');

            $table->index('status');

            $table->index('start_datetime');

            $table->index('end_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};