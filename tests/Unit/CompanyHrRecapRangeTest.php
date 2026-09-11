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

    public function test_period_filters_support_day_month_and_year_ranges(): void
    {
        $service = new CompanyHrRecapService(new WorkCalendarService());

        [$dayFrom, $dayTo] = $service->resolveRange(Request::create('/', 'GET', [
            'period' => 'day', 'day' => '2026-09-10',
        ]));
        $this->assertSame('2026-09-10', $dayFrom->toDateString());
        $this->assertSame('2026-09-10', $dayTo->toDateString());

        [$monthFrom, $monthTo] = $service->resolveRange(Request::create('/', 'GET', [
            'period' => 'month', 'from_month' => '2026-07', 'to_month' => '2026-09',
        ]));
        $this->assertSame('2026-07-01', $monthFrom->toDateString());
        $this->assertSame('2026-09-11', $monthTo->toDateString());

        [$yearFrom, $yearTo] = $service->resolveRange(Request::create('/', 'GET', [
            'period' => 'year', 'from_year' => '2024', 'to_year' => '2026',
        ]));
        $this->assertSame('2024-01-01', $yearFrom->toDateString());
        $this->assertSame('2026-09-11', $yearTo->toDateString());
    }
}
