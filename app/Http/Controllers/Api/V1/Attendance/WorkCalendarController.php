<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\CompanyHoliday;
use App\Services\Attendance\WorkCalendarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkCalendarController extends Controller
{
    public function index(Request $request, WorkCalendarService $calendar): JsonResponse
    {
        $company = $request->user()->company;
        abort_unless($company, 403);
        $schedule = $calendar->scheduleFor($company);

        $holidays = CompanyHoliday::query()
            ->where('company_id', $company->id)
            ->orderBy('start_date')
            ->get()
            ->map(fn ($holiday) => [
                'id' => $holiday->id,
                'name' => $holiday->name,
                'start_date' => $holiday->start_date->toDateString(),
                'end_date' => $holiday->end_date->toDateString(),
                'type' => $holiday->type,
                'description' => $holiday->description,
            ]);

        return ResponseHelper::success([
            'schedule' => collect(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'])
                ->mapWithKeys(fn ($day) => [$day => (bool) $schedule->{$day}]),
            'holidays' => $holidays,
            'today' => $calendar->dayInfo($company, today()),
        ], 'Kalender kerja berhasil diambil.');
    }

    public function updateSchedule(Request $request, WorkCalendarService $calendar): JsonResponse
    {
        $company = $request->user()->company;
        abort_unless($company, 403);
        $data = $request->validate([
            'monday'=>'required|boolean','tuesday'=>'required|boolean','wednesday'=>'required|boolean',
            'thursday'=>'required|boolean','friday'=>'required|boolean','saturday'=>'required|boolean','sunday'=>'required|boolean',
        ]);
        $calendar->scheduleFor($company)->update($data);
        return $this->index($request, $calendar);
    }

    public function storeHoliday(Request $request, WorkCalendarService $calendar): JsonResponse
    {
        $company = $request->user()->company;
        abort_unless($company, 403);
        $data = $request->validate([
            'name'=>['required','string','max:150'],
            'start_date'=>['required','date'],
            'end_date'=>['required','date','after_or_equal:start_date'],
            'type'=>['required','in:national,collective_leave,company'],
            'description'=>['nullable','string','max:1000'],
        ]);
        $company->holidays()->create($data);
        return $this->index($request, $calendar);
    }

    public function updateHoliday(Request $request, CompanyHoliday $holiday, WorkCalendarService $calendar): JsonResponse
    {
        $company = $request->user()->company;
        abort_unless($company && $holiday->company_id === $company->id, 403);
        $data = $request->validate([
            'name'=>['required','string','max:150'],
            'start_date'=>['required','date'],
            'end_date'=>['required','date','after_or_equal:start_date'],
            'type'=>['required','in:national,collective_leave,company'],
            'description'=>['nullable','string','max:1000'],
        ]);
        $holiday->update($data);
        return $this->index($request, $calendar);
    }

    public function destroyHoliday(Request $request, CompanyHoliday $holiday, WorkCalendarService $calendar): JsonResponse
    {
        $company = $request->user()->company;
        abort_unless($company && $holiday->company_id === $company->id, 403);
        $holiday->delete();
        return $this->index($request, $calendar);
    }
}
