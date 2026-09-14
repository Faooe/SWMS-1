<?php

namespace Tests\Unit;

use App\Support\Pagination;
use Tests\TestCase;

class PaginationTest extends TestCase
{
    public function test_requested_page_size_is_clamped_to_bounds(): void
    {
        $this->assertSame(10, Pagination::normalize(null));
        $this->assertSame(1, Pagination::normalize(0));
        $this->assertSame(10, Pagination::normalize(999));
        $this->assertSame(25, Pagination::normalize('25', 15, 100));
        $this->assertSame(10, Pagination::normalize('invalid', 10, 100, 10));
    }
}
