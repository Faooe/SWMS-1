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
        Schema::create('leave_requests', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            /*
            |--------------------------------------------------------------------------
            | Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId('company_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Leave Detail
            |--------------------------------------------------------------------------
            */

            // Contoh: Sakit, Acara/Keperluan Pribadi
            $table->string('type', 50);

            $table->date('start_date');

            $table->date('end_date');

            $table->text('reason');

            $table->string('attachment')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Approval Workflow
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [

                'Pending',

                'Approved',

                'Rejected',

            ])->default('Pending');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('employee_id');

            $table->index('status');

            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
