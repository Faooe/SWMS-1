<?php

namespace App\Console\Commands;

use App\Services\AssignmentService;
use Illuminate\Console\Command;

class ActivateScheduledAssignments extends Command
{
    protected $signature = 'assignments:activate-scheduled';

    protected $description = 'Ubah status Assignment dari Draft menjadi Assigned otomatis ketika jadwal (start_datetime) sudah tiba.';

    public function handle(AssignmentService $assignmentService): int
    {

        $count = $assignmentService->activateScheduledDrafts();

        $this->info("Activated {$count} scheduled assignment(s) from Draft to Assigned.");

        return self::SUCCESS;

    }
}
