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
        Schema::create('assignment_logs', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId('assignment_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('employee_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Activity
            |--------------------------------------------------------------------------
            */

            $table->string('action', 100);

            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Extra Data (Future)
            |--------------------------------------------------------------------------
            */

            $table->json('properties')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_logs');
    }
};