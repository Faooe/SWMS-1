<?php

namespace App\Livewire\Attendance;

use App\Models\Office;
use App\Services\AttendanceManagementService;
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

    protected $paginationTheme = 'tailwind';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'office', 'status', 'date'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'office', 'status', 'date']);
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
        return view('livewire.attendance.manager', [
            'attendances' => $attendanceService->getAttendances($this->filters()),
            'statistics' => $attendanceService->statistics(),
            'offices' => Office::query()
                ->forCurrentCompany()
                ->orderBy('name')
                ->get(),
            'exportUrl' => route('attendance.export.pdf', $this->filters()),
        ]);
    }
}
