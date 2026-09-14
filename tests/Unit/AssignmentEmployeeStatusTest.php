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
        $this->assertSame([
            AssignmentEmployee::STATUS_ASSIGNED,
            AssignmentEmployee::STATUS_ACCEPTED,
            AssignmentEmployee::STATUS_IN_PROGRESS,
        ], AssignmentEmployee::activeStatuses());
        $this->assertSame([
            AssignmentEmployee::REVIEW_NOT_WORKED,
            AssignmentEmployee::REVIEW_EXPIRED,
        ], AssignmentEmployee::notWorkedReviewStatuses());
    }
}
