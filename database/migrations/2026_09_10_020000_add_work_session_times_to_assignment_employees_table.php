<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignment_employees', function (Blueprint $table) {
            $table->timestamp('work_check_in_at')->nullable()->after('started_at');
            $table->timestamp('work_check_out_at')->nullable()->after('work_check_in_at');
        });
    }

    public function down(): void
    {
        Schema::table('assignment_employees', function (Blueprint $table) {
            $table->dropColumn(['work_check_in_at', 'work_check_out_at']);
        });
    }
};
