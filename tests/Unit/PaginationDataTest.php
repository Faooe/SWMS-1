<?php

namespace Tests\Unit;

use App\Support\PaginationData;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\TestCase;

class PaginationDataTest extends TestCase
{
    public function test_api_pagination_metadata_keeps_the_existing_contract(): void
    {
        $paginator = new LengthAwarePaginator(range(11, 20), 24, 10, 2);

        $this->assertSame([
            'current_page' => 2,
            'last_page' => 3,
            'per_page' => 10,
            'total' => 24,
        ], PaginationData::from($paginator));
    }
}
