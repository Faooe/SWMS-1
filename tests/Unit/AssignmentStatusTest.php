<?php

namespace Tests\Unit;

use App\Models\Assignment;
use Tests\TestCase;

class AssignmentStatusTest extends TestCase
{
    public function test_status_helpers_use_the_canonical_assignment_values(): void
    {
        $assignment = new Assignment;
        $assignment->status = Assignment::STATUS_IN_PROGRESS;

        $this->assertTrue($assignment->isInProgress());
        $this->assertTrue($assignment->isActive());
        $this->assertFalse($assignment->isFinished());
        $this->assertFalse($assignment->canBeDeleted());
    }
}
