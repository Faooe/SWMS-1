<?php

namespace Tests\Unit;

use App\Models\Assignment;
use App\Models\AssignmentEmployee;
use App\Models\Employee;
use App\Models\User;
use App\Services\AssignmentAssignedNotifier;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AssignmentAssignedNotifierTest extends TestCase
{
    public function test_assignment_notification_is_created_only_once(): void
    {
        Schema::create('notifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        $user = new User;
        $user->id = 7;

        $employee = new Employee;
        $employee->id = 4;
        $employee->setRelation('user', $user);

        $assignment = new Assignment;
        $assignment->id = 11;
        $assignment->title = 'Inspeksi kantor';
        $assignment->start_datetime = '2026-09-14 08:00:00';

        $recipient = new AssignmentEmployee;
        $recipient->id = 19;
        $recipient->assignment_id = $assignment->id;
        $recipient->employee_id = $employee->id;
        $recipient->setRelation('assignment', $assignment);
        $recipient->setRelation('employee', $employee);

        $notifier = new AssignmentAssignedNotifier;

        $this->assertTrue($notifier->send($recipient));
        $this->assertFalse($notifier->send($recipient));
        $this->assertSame(1, $user->notifications()->count());
    }
}
