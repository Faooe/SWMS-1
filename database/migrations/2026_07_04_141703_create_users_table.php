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
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            |
            | Platform Admin tidak memiliki company.
            | Company akan ditambahkan melalui migration terpisah.
            |
            */

            // company_id ditambahkan pada migration lain.

            /*
            |--------------------------------------------------------------------------
            | Employee
            |--------------------------------------------------------------------------
            */

            $table->foreignId('employee_id')

                ->nullable()

                ->unique()

                ->constrained()

                ->nullOnDelete()

                ->cascadeOnUpdate();

            /*
            |--------------------------------------------------------------------------
            | Role
            |--------------------------------------------------------------------------
            */

            $table->foreignId('role_id')

                ->constrained()

                ->cascadeOnUpdate()

                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            */

            $table->string('username', 50)->unique();

            $table->string('email', 150)->unique();

            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');

            /*
            |--------------------------------------------------------------------------
            | Security
            |--------------------------------------------------------------------------
            */

            $table->timestamp('password_changed_at')->nullable();

            $table->timestamp('last_login_at')->nullable();

            $table->string('last_login_ip', 45)->nullable();

            $table->boolean('is_active')->default(true);

            $table->rememberToken();

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('username');

            $table->index('email');

            $table->index('role_id');

            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};