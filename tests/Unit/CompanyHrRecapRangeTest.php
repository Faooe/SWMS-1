<?php

namespace Tests\Unit;

use App\Services\Attendance\WorkCalendarService;
use App\Services\CompanyHrRecapService;
use Illuminate\Http\Request;
use Tests\TestCase;

class CompanyHrRecapRangeTest extends TestCase
{
    public function test_recap_uses_exact_dates_without_rounding_to_month_end(): void
    {
        $request = Request::create('/', 'GET', [
            'from' => '2026-07-01',
            'to' => '2026-09-10',
        ]);

        [$from, $to] = (new CompanyHrRecapService(new WorkCalendarService()))
            ->resolveRange($request);

        $this->assertSame('2026-07-01', $from->toDateString());
        $this->assertSame('2026-09-10', $to->toDateString());
    }

    public function test_recap_normalizes_a_reversed_date_range(): void
    {
        $request = Request::create('/', 'GET', [
            'from' => '2026-09-10',
            'to' => '2026-07-01',
        ]);

        [$from, $to] = (new CompanyHrRecapService(new WorkCalendarService()))
            ->resolveRange($request);

        $this->assertSame('2026-07-01', $from->toDateString());
        $this->assertSame('2026-09-10', $to->toDateString());
    }
}
