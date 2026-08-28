<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CompanyHoliday;
use App\Services\Attendance\WorkCalendarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WorkCalendarController extends Controller
{
    public function index(WorkCalendarService $calendar): View
    {
        $company = Auth::user()->company;
        abort_unless($company, 403);

        return view('attendance.work-calendar', [
            'schedule' => $calendar->scheduleFor($company),
            'todayInfo' => $calendar->dayInfo($company, today()),
            'holidays' => CompanyHoliday::query()
                ->where('company_id', $company->id)
                ->orderByDesc('start_date')
                ->get(),
        ]);
    }

    public function updateSchedule(Request $request, WorkCalendarService $calendar): RedirectResponse
    {
        $company = $request->user()->company;
        abort_unless($company, 403);

        $schedule = $calendar->scheduleFor($company);
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        $payload = [];
        foreach ($days as $day) {
            $payload[$day] = $request->boolean($day);
        }
        $schedule->update($payload);

        return back()->with('success', 'Hari kerja berhasil diperbarui.');
    }

    public function storeHoliday(Request $request): RedirectResponse
    {
        $company = $request->user()->company;
        abort_unless($company, 403);

        $data = $request->validate([
            'name' => ['required','string','max:150'],
            'start_date' => ['required','date'],
            'end_date' => ['required','date','after_or_equal:start_date'],
            'type' => ['required','in:national,collective_leave,company'],
            'description' => ['nullable','string','max:1000'],
        ]);

        $company->holidays()->create($data);
        return back()->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function editHoliday(Request $request, CompanyHoliday $holiday): View
    {
        $company = $request->user()->company;
        abort_unless($company && $holiday->company_id === $company->id, 403);
        return view('attendance.edit-holiday', compact('holiday'));
    }

    public function updateHoliday(Request $request, CompanyHoliday $holiday): RedirectResponse
    {
        $company = $request->user()->company;
        abort_unless($company && $holiday->company_id === $company->id, 403);

        $data = $request->validate([
            'name' => ['required','string','max:150'],
            'start_date' => ['required','date'],
            'end_date' => ['required','date','after_or_equal:start_date'],
            'type' => ['required','in:national,collective_leave,company'],
            'description' => ['nullable','string','max:1000'],
        ]);
        $holiday->update($data);
        return back()->with('success', 'Hari libur berhasil diperbarui.');
    }

    public function destroyHoliday(Request $request, CompanyHoliday $holiday): RedirectResponse
    {
        $company = $request->user()->company;
        abort_unless($company && $holiday->company_id === $company->id, 403);
        $holiday->delete();
        return back()->with('success', 'Hari libur dihapus.');
    }
}
