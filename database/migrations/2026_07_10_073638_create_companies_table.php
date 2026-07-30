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
        Schema::create('companies', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Identity
            |--------------------------------------------------------------------------
            */

            $table->uuid('uuid')->unique();

            $table->string('code', 30)->unique();

            $table->string('name', 150);

            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */

            $table->string('email')->nullable();

            $table->string('phone', 30)->nullable();

            $table->string('website')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Logo
            |--------------------------------------------------------------------------
            */

            $table->string('logo')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            $table->text('address')->nullable();

            $table->string('city', 100)->nullable();

            $table->string('province', 100)->nullable();

            $table->string('postal_code', 15)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timezone
            |--------------------------------------------------------------------------
            */

            $table->string('timezone')
                ->default('Asia/Makassar');

            /*
            |--------------------------------------------------------------------------
            | Subscription
            |--------------------------------------------------------------------------
            */

            $table->string('subscription_plan', 50)
                ->default('Free');

            $table->date('subscription_start')
                ->nullable();

            $table->date('subscription_end')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Limit
            |--------------------------------------------------------------------------
            */

            $table->integer('max_employee')
                ->default(50);

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('code');

            $table->index('is_active');

            $table->index('subscription_end');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};