<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('shifts', function (Blueprint $table) {

        $table->id();

        $table->uuid('uuid')->unique();

        $table->string('code', 20)->unique();

        $table->string('name', 100);

        $table->time('start_time');

        $table->time('end_time');

        $table->time('break_start')->nullable();

        $table->time('break_end')->nullable();

        $table->integer('late_tolerance')->default(15);

        $table->json('work_days');

        $table->boolean('is_night_shift')->default(false);

        $table->boolean('is_active')->default(true);

        $table->timestamps();

        $table->softDeletes();

        $table->index('code');
        $table->index('is_active');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
