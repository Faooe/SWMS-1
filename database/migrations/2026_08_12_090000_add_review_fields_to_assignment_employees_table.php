<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Alur Review Hasil Pekerjaan Assignment
    |--------------------------------------------------------------------------
    |
    | Sebelumnya employee cuma upload 1 foto bukti selesai, tanpa ada
    | proses review dari company sama sekali (begitu upload, langsung
    | dianggap final). Sekarang:
    |
    | 1. Employee upload 2 foto (completion_photo, completion_photo_2 --
    |    foto kedua opsional) + completion_notes (detail pekerjaan yang
    |    dilakukan).
    | 2. Company review lewat review_status: 'Pending Review' (nunggu
    |    dicek) -> 'Approved' (selesai, beres) ATAU 'Needs Revision'
    |    (company reject, employee harus perbaiki -- review_notes diisi
    |    company sebagai catatan apa yang perlu diperbaiki).
    | 3. Kalau 'Needs Revision', revision_deadline_at dihitung dari saat
    |    di-reject + durasi revisi (default per company di
    |    companies.assignment_revision_minutes, atau admin override
    |    manual saat reject). Employee WAJIB upload ulang 2 foto + notes
    |    (bukan nambahin, tapi timpa yang lama -- field completion_photo/
    |    completion_photo_2/completion_notes cuma nyimpen submission
    |    TERAKHIR, tidak ada riwayat per-submission).
    | 4. Kalau resubmit dalam batas revision_deadline_at -> normal.
    |    Lewat deadline tapi masih dalam grace 30 menit -> tetap boleh
    |    resubmit tapi is_late_revision jadi true ("Late Pengerjaan").
    |    Lewat lebih dari 2 jam dari deadline -> tidak boleh resubmit
    |    lagi, review_status otomatis jadi 'Expired' lewat scheduled job
    |    (lihat App\Console\Commands\ExpireAssignmentRevisions).
    |
    | review_status SENGAJA field baru terpisah dari `status` yang sudah
    | ada (Assigned/Accepted/In Progress/Completed/Rejected) -- supaya
    | tidak bentrok makna dengan 'Rejected' yang sudah dipakai untuk
    | "employee menolak assignment ini" (beda konsep dari "company
    | menolak hasil kerjanya").
    |
    */

    public function up(): void
    {
        Schema::table('assignment_employees', function (Blueprint $table) {

            $table->string('completion_photo_2')->nullable()->after('completion_photo');

            $table->text('completion_notes')->nullable()->after('completion_photo_2');

            $table->string('review_status')->nullable()->after('completion_notes');

            $table->text('review_notes')->nullable()->after('review_status');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->after('review_notes')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');

            $table->timestamp('revision_deadline_at')->nullable()->after('reviewed_at');

            $table->boolean('is_late_revision')->default(false)->after('revision_deadline_at');

            $table->unsignedInteger('revision_count')->default(0)->after('is_late_revision');

        });
    }

    public function down(): void
    {
        Schema::table('assignment_employees', function (Blueprint $table) {

            $table->dropConstrainedForeignId('reviewed_by');

            $table->dropColumn([
                'completion_photo_2',
                'completion_notes',
                'review_status',
                'review_notes',
                'reviewed_at',
                'revision_deadline_at',
                'is_late_revision',
                'revision_count',
            ]);

        });
    }
};
