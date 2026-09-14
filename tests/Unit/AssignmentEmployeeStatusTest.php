<?php

namespace Tests\Unit;

use App\Models\AssignmentEmployee;
use Tests\TestCase;

class AssignmentEmployeeStatusTest extends TestCase
{
    public function test_workflow_and_review_helpers_use_the_canonical_status_values(): void
    {
        $pivot = new AssignmentEmployee;
        $pivot->status = AssignmentEmployee::STATUS_IN_PROGRESS;
        $pivot->review_status = AssignmentEmployee::REVIEW_NEEDS_REVISION;

        $this->assertTrue($pivot->isInProgress());
        $this->assertTrue($pivot->needsRevision());
        $this->assertTrue($pivot->canCheckOut());
        $this->assertTrue($pivot->canSubmitCompletion());
    }
}
