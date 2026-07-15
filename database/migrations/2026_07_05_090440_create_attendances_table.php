<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            /*
            |--------------------------------------------------------------------------
            | Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('office_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('assignment_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('shift_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Attendance Type
            |--------------------------------------------------------------------------
            */

            $table->enum('attendance_type', [

                'OFFICE',

                'ASSIGNMENT',

            ])->default('OFFICE');

            /*
            |--------------------------------------------------------------------------
            | Attendance Date
            |--------------------------------------------------------------------------
            */

            $table->date('attendance_date');

            /*
            |--------------------------------------------------------------------------
            | Check In
            |--------------------------------------------------------------------------
            */

            $table->time('check_in_time')->nullable();

            $table->decimal('check_in_latitude', 10, 7)->nullable();

            $table->decimal('check_in_longitude', 10, 7)->nullable();

            $table->decimal('check_in_distance', 8, 2)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Check Out
            |--------------------------------------------------------------------------
            */

            $table->time('check_out_time')->nullable();

            $table->decimal('check_out_latitude', 10, 7)->nullable();

            $table->decimal('check_out_longitude', 10, 7)->nullable();

            $table->decimal('check_out_distance', 8, 2)->nullable();

            /*
            |--------------------------------------------------------------------------
            | GPS Validation
            |--------------------------------------------------------------------------
            */

            $table->integer('allowed_radius')->nullable();

            $table->boolean('location_verified')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Attendance Status
            |--------------------------------------------------------------------------
            */

            $table->enum('attendance_status', [

                'Present',

                'Late',

                'Absent',

                'Leave',

                'Permission',

                'Holiday',

            ])->default('Present');

            /*
            |--------------------------------------------------------------------------
            | Progress
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_checked_in')
                ->default(false);

            $table->boolean('is_checked_out')
                ->default(false);

            $table->integer('late_minutes')
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('attendance_date');

            $table->index('employee_id');

            $table->index('office_id');

            $table->index('assignment_id');

            $table->index('attendance_type');

            $table->index('attendance_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};