<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedInteger('work_minutes')->default(0)->after('late_minutes');
            $table->unsignedInteger('early_leave_minutes')->default(0)->after('work_minutes');
            $table->unsignedInteger('overtime_minutes')->default(0)->after('early_leave_minutes');
        });

        Schema::create('assignment_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();
            $table->index(['assignment_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_attachments');
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['work_minutes', 'early_leave_minutes', 'overtime_minutes']);
        });
    }
};
