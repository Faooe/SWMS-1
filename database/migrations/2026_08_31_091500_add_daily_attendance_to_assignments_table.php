<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('assignments', function (Blueprint $table) {
            $table->boolean('daily_attendance_enabled')->default(false)->after('end_datetime');
            $table->string('attendance_day_rule', 20)->default('WORK_CALENDAR')->after('daily_attendance_enabled');
        });
    }
    public function down(): void {
        Schema::table('assignments', fn (Blueprint $table) => $table->dropColumn(['daily_attendance_enabled','attendance_day_rule']));
    }
};
