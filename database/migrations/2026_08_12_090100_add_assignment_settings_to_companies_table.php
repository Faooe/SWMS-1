<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {

            // Default batas waktu revisi assignment (dalam menit) --
            // dipakai kalau admin reject hasil kerja TANPA override
            // manual. 1440 menit = 24 jam.
            $table->unsignedInteger('assignment_revision_minutes')->default(1440)->after('max_employee');

            // Kalau true, hasil kerja assignment employee otomatis
            // ter-approve begitu di-submit (skip proses review manual
            // company). Lihat App\Services\EmployeeAssignmentService::complete().
            $table->boolean('assignment_auto_approve')->default(false)->after('assignment_revision_minutes');

        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {

            $table->dropColumn([
                'assignment_revision_minutes',
                'assignment_auto_approve',
            ]);

        });
    }
};
