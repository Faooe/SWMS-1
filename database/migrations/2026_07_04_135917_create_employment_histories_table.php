<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('employment_histories', function (Blueprint $table) {

        $table->id();

        $table->foreignId('employee_id')
            ->constrained()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

        $table->foreignId('department_id')
            ->constrained()
            ->cascadeOnUpdate()
            ->restrictOnDelete();

        $table->foreignId('position_id')
            ->constrained()
            ->cascadeOnUpdate()
            ->restrictOnDelete();

        $table->foreignId('team_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->foreignId('office_id')
            ->constrained()
            ->cascadeOnUpdate()
            ->restrictOnDelete();

        $table->foreignId('shift_id')
            ->constrained()
            ->cascadeOnUpdate()
            ->restrictOnDelete();

        $table->foreignId('supervisor_id')
            ->nullable()
            ->constrained('employees')
            ->nullOnDelete();

        $table->enum('employment_type', [
            'Permanent',
            'Contract',
            'Internship'
        ])->default('Permanent');

        $table->enum('employment_status', [
            'Active',
            'Resigned',
            'Retired',
            'Suspended'
        ])->default('Active');

        $table->date('start_date');

        $table->date('end_date')->nullable();

        $table->boolean('is_current')->default(true);

        $table->timestamps();

        $table->index('employee_id');
        $table->index('office_id');
        $table->index('shift_id');
        $table->index('is_current');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_histories');
    }
};