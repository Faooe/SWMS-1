<?php

namespace App\Console\Commands;

use App\Services\Attendance\AbsentAttendanceService;
use Illuminate\Console\Command;

class MarkAbsentEmployees extends Command
{
    protected $signature = 'attendance:mark-absent {--days=3 : Jumlah hari ke belakang yang dicek, termasuk hari ini}';

    protected $description = 'Tandai karyawan yang tidak check-in dan tidak memiliki izin sebagai Absent (Alpa/Mangkir).';

    public function handle(AbsentAttendanceService $absentAttendanceService): int
    {

        $lookbackDays = (int) $this->option('days');

        $count = $absentAttendanceService->markAbsentForRecentDays($lookbackDays);

        $this->info("Marked {$count} employee(s) as Absent (checked last {$lookbackDays} day(s)).");

        return self::SUCCESS;

    }
}
