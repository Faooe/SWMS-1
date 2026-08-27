<?php

namespace App\Livewire\Attendance;

use App\Models\Office;
use App\Services\AttendanceManagementService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Manager extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $office = '';

    #[Url(history: true)]
    public string $status = '';

    #[Url(history: true)]
    public string $date = '';

    public int $perPage = 10;

    public string $exportMonth = '';

    public string $analyticsPeriod = 'day';

    public string $analyticsDate = '';

    public string $analyticsMonth = '';

    public string $analyticsYear = '';

    public string $analyticsEmployeeSearch = '';

    protected $paginationTheme = 'tailwind';

    public function mount(): void
    {
        // Default: cuma tampilkan attendance HARI INI. History hari
        // sebelumnya nggak hilang, tetap bisa dicari lewat filter tanggal.
        if ($this->date === '') {
            $this->date = today()->toDateString();
        }

        $this->exportMonth = today()->format('Y-m');
        $this->analyticsDate = today()->toDateString();
        $this->analyticsMonth = today()->format('Y-m');
        $this->analyticsYear = (string) today()->year;
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'office', 'status', 'date'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'office', 'status']);

        $this->date = today()->toDateString();

        $this->resetPage();
    }

    public function showAllDates(): void
    {
        $this->date = '';

        $this->resetPage();
    }

    protected function filters(): array
    {
        return [
            'search' => $this->search,
            'office' => $this->office,
            'status' => $this->status,
            'date' => $this->date,
            'per_page' => $this->perPage,
        ];
    }

    public function render(AttendanceManagementService $attendanceService)
    {
        $company = Auth::user()->company;

        $exportParams = array_filter([
            'search' => $this->search,
            'office' => $this->office,
            'status' => $this->status,
            'month' => $this->exportMonth,
        ]);

        $isPremium = $company?->isPremium() ?? false;
        $analytics = null;

        if ($isPremium) {
            $analyticsYear = (int) ($this->analyticsYear ?: today()->year);
            $analyticsMonth = null;

            if ($this->analyticsMonth && preg_match('/^(\d{4})-(\d{2})$/', $this->analyticsMonth, $matches)) {
                $analyticsYear = (int) $matches[1];
                $analyticsMonth = (int) $matches[2];
            }

            $analytics = $attendanceService->analytics(
                $this->analyticsPeriod,
                $this->analyticsDate ?: null,
                $analyticsYear,
                $analyticsMonth
            );

            if ($this->analyticsEmployeeSearch !== '' && isset($analytics['by_employee'])) {
                $needle = mb_strtolower(trim($this->analyticsEmployeeSearch));

                $analytics['by_employee'] = collect($analytics['by_employee'])
                    ->filter(function (array $row) use ($needle): bool {
                        $name = mb_strtolower((string) ($row['employee_name'] ?? ''));
                        $number = mb_strtolower((string) ($row['employee_number'] ?? ''));

                        return str_contains($name, $needle) || str_contains($number, $needle);
                    })
                    ->values()
                    ->all();
            }
        }

        return view('livewire.attendance.manager', [
            'attendances' => $attendanceService->getAttendances($this->filters()),
            'statistics' => $attendanceService->statistics(),
            'offices' => Office::query()
                ->forCurrentCompany()
                ->orderBy('name')
                ->get(),
            'isToday' => $this->date === today()->toDateString(),
            'isPremium' => $isPremium,
            'analytics' => $analytics,
            'exportPdfUrl' => route('attendance.export.pdf', $exportParams),
            'exportExcelUrl' => route('attendance.export.excel', $exportParams),
        ]);
    }
}
