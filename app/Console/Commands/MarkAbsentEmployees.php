<?php

namespace App\Console\Commands;

use App\Services\Attendance\AbsentAttendanceService;
use Illuminate\Console\Command;

class MarkAbsentEmployees extends Command
{
    protected $signature = 'attendance:mark-absent';

    protected $description = 'Tandai karyawan yang tidak check-in dan tidak memiliki izin sebagai Absent (Alpa/Mangkir).';

    public function handle(AbsentAttendanceService $absentAttendanceService): int
    {

        $count = $absentAttendanceService->markAbsentForToday();

        $this->info("Marked {$count} employee(s) as Absent for today.");

        return self::SUCCESS;

    }
}
