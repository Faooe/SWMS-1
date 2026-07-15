<?php

namespace App\Livewire;

use App\Models\Position;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PositionManager extends Component
{
    /*
    |--------------------------------------------------------------------------
    | Form State
    |--------------------------------------------------------------------------
    */

    public ?int $editingId = null;

    public string $code = '';

    public string $name = '';

    public ?string $description = null;

    public bool $is_active = true;

    public bool $showForm = false;

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    protected function rules(): array
    {
        $companyId = Auth::user()->company_id;

        return [

            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('positions', 'code')
                    ->where(fn ($query) => $query->where('company_id', $companyId))
                    ->ignore($this->editingId),
            ],

            'name' => ['required', 'string', 'max:100'],

            'description' => ['nullable', 'string'],

            'is_active' => ['boolean'],

        ];
    }

    public function createPosition(): void
    {
        $this->resetForm();

        $this->showForm = true;
    }

    public function editPosition(int $positionId): void
    {
        $position = Position::query()

            ->where('company_id', Auth::user()->company_id)

            ->findOrFail($positionId);

        $this->editingId = $position->id;
        $this->code = $position->code;
        $this->name = $position->name;
        $this->description = $position->description;
        $this->is_active = (bool) $position->is_active;

        $this->showForm = true;
    }

    public function save(): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->validate();

        $companyId = Auth::user()->company_id;

        if ($this->editingId) {

            $position = Position::query()
                ->where('company_id', $companyId)
                ->findOrFail($this->editingId);

            $position->update([
                'code' => $this->code,
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            $this->successMessage = 'Position berhasil diperbarui.';

        } else {

            Position::create([
                'company_id' => $companyId,
                'code' => $this->code,
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            $this->successMessage = 'Position berhasil ditambahkan.';

        }

        $this->resetForm();
    }

    public function toggleStatus(int $positionId): void
    {
        $position = Position::query()
            ->where('company_id', Auth::user()->company_id)
            ->findOrFail($positionId);

        $position->update([
            'is_active' => !$position->is_active,
        ]);
    }

    public function deletePosition(int $positionId): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $position = Position::query()
            ->where('company_id', Auth::user()->company_id)
            ->findOrFail($positionId);

        if ($position->employmentHistories()->exists()) {

            $this->errorMessage = 'Position tidak bisa dihapus karena masih digunakan oleh Employee.';

            return;

        }

        $position->delete();

        $this->successMessage = 'Position berhasil dihapus.';
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'code', 'name', 'description', 'showForm']);

        $this->is_active = true;

        $this->resetErrorBag();
    }

    public function render()
    {
        $positions = Position::query()

            ->where('company_id', Auth::user()->company_id)

            ->withCount('employmentHistories')

            ->orderBy('name')

            ->get();

        return view('livewire.position-manager', [
            'positions' => $positions,
        ]);
    }
}
