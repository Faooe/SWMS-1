<?php

namespace App\Livewire\Team;

use App\Models\Department;
use App\Models\Team;
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
    public string $department = '';

    #[Url(history: true)]
    public string $isActive = '';

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    protected $paginationTheme = 'tailwind';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'department', 'isActive'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'department', 'isActive']);
        $this->resetPage();
    }

    private function authorizeCompany(Team $team): void
    {
        abort_unless(
            $team->company_id === Auth::user()->company_id,
            403,
            'Anda tidak memiliki akses ke team ini.'
        );
    }

    public function toggleStatus(int $teamId): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $team = Team::findOrFail($teamId);
            $this->authorizeCompany($team);

            $team->update(['is_active' => !$team->is_active]);

            $this->successMessage = $team->is_active
                ? 'Team berhasil diaktifkan.'
                : 'Team berhasil dinonaktifkan.';

            $this->dispatch('action-complete');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal mengubah status team.';
            $this->dispatch('action-loading-done');

        }
    }

    public function deleteTeam(int $teamId): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $team = Team::findOrFail($teamId);
            $this->authorizeCompany($team);

            if ($team->employmentHistories()->exists()) {

                $this->errorMessage = 'Team tidak bisa dihapus karena masih digunakan oleh Employee.';
                $this->dispatch('action-loading-done');

                return;
            }

            $team->delete();

            $this->successMessage = 'Team berhasil dihapus.';
            $this->dispatch('action-complete');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal menghapus team.';
            $this->dispatch('action-loading-done');

        }
    }

    public function render()
    {
        $companyId = Auth::user()->company_id;

        $teams = Team::query()
            ->forCurrentCompany()
            ->with('department')
            ->withCount('employmentHistories')
            ->when($this->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($this->department, fn ($query) => $query->where('department_id', $this->department))
            ->when($this->isActive !== '', fn ($query) => $query->where('is_active', $this->isActive === '1'))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.team.manager', [
            'teams' => $teams,
            'departments' => Department::forCurrentCompany()->orderBy('name')->get(),
            'statistics' => [
                'total' => Team::forCurrentCompany()->count(),
                'active' => Team::forCurrentCompany()->where('is_active', true)->count(),
                'inactive' => Team::forCurrentCompany()->where('is_active', false)->count(),
            ],
        ]);
    }
}
