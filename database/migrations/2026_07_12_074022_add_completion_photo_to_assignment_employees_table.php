<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignment_employees', function (Blueprint $table) {

            $table->string('completion_photo')->nullable()->after('finished_at');

        });
    }

    public function down(): void
    {
        Schema::table('assignment_employees', function (Blueprint $table) {

            $table->dropColumn('completion_photo');

        });
    }
};