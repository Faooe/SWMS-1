<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->text('daily_report_notes')->nullable()->after('notes');
            $table->json('daily_report_photos')->nullable()->after('daily_report_notes');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['daily_report_notes', 'daily_report_photos']);
        });
    }
};
