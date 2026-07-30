<?php

namespace App\Livewire\Position;

use App\Models\Position;
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
    public string $isActive = '';

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    protected $paginationTheme = 'tailwind';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'isActive'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'isActive']);
        $this->resetPage();
    }

    private function authorizeCompany(Position $position): void
    {
        abort_unless(
            $position->company_id === Auth::user()->company_id,
            403,
            'Anda tidak memiliki akses ke position ini.'
        );
    }

    public function toggleStatus(int $positionId): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $position = Position::findOrFail($positionId);
            $this->authorizeCompany($position);

            $position->update(['is_active' => !$position->is_active]);

            $this->successMessage = $position->is_active
                ? 'Position berhasil diaktifkan.'
                : 'Position berhasil dinonaktifkan.';

            $this->dispatch('action-complete');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal mengubah status position.';
            $this->dispatch('action-loading-done');

        }
    }

    public function deletePosition(int $positionId): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $position = Position::findOrFail($positionId);
            $this->authorizeCompany($position);

            if ($position->employmentHistories()->exists()) {

                $this->errorMessage = 'Position tidak bisa dihapus karena masih digunakan oleh Employee.';
                $this->dispatch('action-loading-done');

                return;
            }

            $position->delete();

            $this->successMessage = 'Position berhasil dihapus.';
            $this->dispatch('action-complete');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal menghapus position.';
            $this->dispatch('action-loading-done');

        }
    }

    public function render()
    {
        $positions = Position::query()
            ->forCurrentCompany()
            ->withCount('employmentHistories')
            ->when($this->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($this->isActive !== '', fn ($query) => $query->where('is_active', $this->isActive === '1'))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.position.manager', [
            'positions' => $positions,
            'statistics' => [
                'total' => Position::forCurrentCompany()->count(),
                'active' => Position::forCurrentCompany()->where('is_active', true)->count(),
                'inactive' => Position::forCurrentCompany()->where('is_active', false)->count(),
            ],
        ]);
    }
}
