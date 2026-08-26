<?php

namespace App\Console\Commands;

use App\Services\LeaveRequestService;
use Illuminate\Console\Command;

class AutoRejectExpiredLeaveRequests extends Command
{
    protected $signature = 'leave-requests:auto-reject-expired';

    protected $description = 'Auto reject pengajuan izin yang masih Pending setelah end_date terlewati.';

    public function handle(LeaveRequestService $leaveRequestService): int
    {
        $count = $leaveRequestService->autoRejectExpiredPending();

        $this->info("Auto rejected {$count} expired pending leave request(s).");

        return self::SUCCESS;
    }
}
