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
        Schema::create('employees', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('employee_number',30)->unique();

            $table->string('full_name',150);

            $table->string('email',150)->unique();

            $table->string('phone',20)->nullable();

            $table->enum('gender',['Male','Female']);

            $table->string('birth_place',100)->nullable();

            $table->date('birth_date')->nullable();

            $table->text('address')->nullable();

            $table->string('identity_number',30)->nullable();

            $table->enum('marital_status',[
                'Single',
                'Married'
            ])->nullable();

            $table->string('photo')->nullable();

            $table->string('emergency_contact_name',100)->nullable();

            $table->string('emergency_contact_phone',20)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->softDeletes();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};