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
        Schema::create('offices', function (Blueprint $table) {

            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('code',20)->unique();
            $table->string('name',150);
            $table->text('address');
            $table->decimal('latitude',10,7)->nullable();
            $table->decimal('longitude',10,7)->nullable();
            $table->integer('radius')->default(200);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index('code');
            $table->index('is_active');
            $table->string('city',100)->nullable();
            $table->string('province',100)->nullable();
            $table->string('postal_code',10)->nullable();
            $table->string('timezone',50)->default('Asia/Makassar');
            $table->boolean('is_head_office')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};