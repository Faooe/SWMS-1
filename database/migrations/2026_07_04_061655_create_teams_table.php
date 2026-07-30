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
        Schema::create('teams', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('code', 20)->unique();

            $table->string('name', 100);

            $table->foreignId('department_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->softDeletes();

            $table->index('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};