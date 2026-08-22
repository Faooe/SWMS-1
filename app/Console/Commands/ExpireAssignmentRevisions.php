<?php

namespace App\Console\Commands;

use App\Models\AssignmentEmployee;
use App\Models\AssignmentLog;
use Illuminate\Console\Command;

/**
 * Flip AssignmentEmployee.review_status dari 'Needs Revision' menjadi
 * 'Expired' begitu revision_deadline_at + toleransi 30 menit sudah
 * kelewat DAN employee belum resubmit. Setelah 'Expired', employee sudah
 * tidak bisa apa-apa lagi terhadap assignment itu -- statusnya tetap
 * "hadir" (attendance tidak terpengaruh, itu record terpisah), tapi
 * laporan pekerjaannya dianggap tidak terselesaikan.
 */
class ExpireAssignmentRevisions extends Command
{
    protected $signature = 'assignments:expire-revisions';

    protected $description = 'Tandai revisi assignment yang sudah kelewat batas waktu (+ toleransi 30 menit) sebagai Expired.';

    public function handle(): int
    {
        $cutoff = now()->subMinutes(30);

        $expired = AssignmentEmployee::query()

            ->where('review_status', 'Needs Revision')

            ->whereNotNull('revision_deadline_at')

            ->where('revision_deadline_at', '<=', $cutoff)

            ->get();

        foreach ($expired as $assignmentEmployee) {

            $assignmentEmployee->update([
                'review_status' => 'Expired',
            ]);

            AssignmentLog::create([

                'assignment_id' => $assignmentEmployee->assignment_id,

                'employee_id' => $assignmentEmployee->employee_id,

                'user_id' => null,

                'action' => 'REVISION_EXPIRED',

                'description' => 'Batas waktu revisi (+ toleransi 30 menit) sudah lewat tanpa resubmit -- otomatis ditandai Expired.',

            ]);

        }

        $count = $expired->count();

        $this->info("Marked {$count} assignment revision(s) as Expired.");

        return self::SUCCESS;
    }
}
