<?php

namespace App\Console\Commands;

use App\Models\AssignmentEmployee;
use App\Models\AssignmentLog;
use App\Notifications\AssignmentNotWorked;
use Illuminate\Console\Command;

/**
 * Sinkronkan assignment yang melewati deadline menjadi "Not Worked".
 * Berlaku untuk revisi yang tidak disubmit ulang dan assignment biasa
 * yang tidak pernah diselesaikan sebelum end_datetime.
 */
class ExpireAssignmentRevisions extends Command
{
    protected $signature = 'assignments:expire-revisions';

    protected $description = 'Tandai assignment/revisi yang melewati deadline tanpa penyelesaian sebagai Tidak Dikerjakan.';

    public function handle(): int
    {
        $rows = AssignmentEmployee::query()
            ->with(['assignment', 'employee.user'])
            ->where(function ($query) {
                $query->where(function ($revision) {
                    $revision->where('review_status', 'Needs Revision')
                        ->whereNotNull('revision_deadline_at')
                        ->where(
                            'revision_deadline_at',
                            '<',
                            now()->subMinutes(AssignmentEmployee::REVISION_BLOCK_THRESHOLD_MINUTES)
                        );
                })->orWhere(function ($assignment) {
                    $assignment->whereNull('review_status')
                        ->whereIn('status', ['Assigned', 'Accepted', 'In Progress'])
                        ->whereHas('assignment', fn ($q) => $q
                            ->whereIn('status', ['Assigned', 'In Progress'])
                            ->where('end_datetime', '<', now()));
                });
            })
            ->get()
            ->filter(function ($row) {
                if ($row->review_status === 'Needs Revision') {
                    return $row->isPastRevisionGracePeriod();
                }

                $assignment = $row->assignment;
                $deadline = $assignment?->end_datetime?->copy();
                if ($deadline && $assignment->daily_attendance_enabled) {
                    $deadline->setTime(23, 0, 0);
                }

                return $deadline && now()->greaterThan($deadline);
            })
            ->values();

        foreach ($rows as $row) {
            $revisionExpired = $row->review_status === 'Needs Revision';
            $assignment = $row->assignment;

            // Daily Attendance tidak boleh dianggap "Not Worked" hanya karena
            // employee melewatkan satu/lebih hari atau tidak sempat submit hasil
            // akhir. Jika pernah Check In minimal satu hari, berarti pekerjaan
            // memang pernah dilakukan dan setelah periode berakhir hasilnya masuk
            // ke Company sebagai Pending Review. Not Worked khusus 0 hari kerja.
            $hasDailyWork = !$revisionExpired
                && (bool) $assignment?->daily_attendance_enabled
                && \App\Models\Attendance::query()
                    ->where('assignment_id', $row->assignment_id)
                    ->where('employee_id', $row->employee_id)
                    ->where('attendance_type', 'ASSIGNMENT')
                    ->where('is_checked_in', true)
                    ->exists();

            if ($hasDailyWork) {
                $row->update([
                    'review_status' => 'Pending Review',
                    'review_notes' => 'Periode Daily Attendance telah berakhir. Riwayat kerja harian menunggu review company.',
                    'reviewed_at' => null,
                ]);

                AssignmentLog::create([
                    'assignment_id' => $row->assignment_id,
                    'employee_id' => $row->employee_id,
                    'user_id' => null,
                    'action' => 'DAILY_ATTENDANCE_PERIOD_ENDED',
                    'description' => 'Periode Daily Attendance berakhir setelah employee pernah bekerja -- otomatis menunggu review company.',
                ]);

                continue;
            }

            $row->update([
                'review_status' => 'Not Worked',
                'review_notes' => $revisionExpired
                    ? 'Batas waktu revisi telah lewat tanpa submit ulang.'
                    : 'Batas waktu assignment telah lewat tanpa pekerjaan yang tercatat.',
                'reviewed_at' => now(),
            ]);

            AssignmentLog::create([
                'assignment_id' => $row->assignment_id,
                'employee_id' => $row->employee_id,
                'user_id' => null,
                'action' => $revisionExpired ? 'REVISION_NOT_WORKED' : 'ASSIGNMENT_NOT_WORKED',
                'description' => $revisionExpired
                    ? 'Batas revisi lewat tanpa submit ulang -- otomatis Tidak Dikerjakan.'
                    : 'Batas assignment lewat dan tidak ada pekerjaan yang tercatat -- otomatis Tidak Dikerjakan.',
            ]);

            $fresh = $row->fresh(['assignment', 'employee.user']);
            $fresh?->employee?->user?->notify(new AssignmentNotWorked($fresh, $revisionExpired));
        }

        $count = $rows->count();
        $this->info("Marked {$count} assignment(s) as Not Worked.");

        return self::SUCCESS;
    }
}
