<?php

namespace App\Livewire\Department;

use App\Models\Department;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TeamManager extends Component
{
    public Department $department;

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

    public function mount(Department $department): void
    {
        abort_unless(
            $department->company_id === Auth::user()->company_id,
            403
        );

        $this->department = $department;
    }

    protected function rules(): array
    {
        $companyId = Auth::user()->company_id;

        return [

            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('teams', 'code')
                    ->where(fn ($query) => $query->where('company_id', $companyId))
                    ->ignore($this->editingId),
            ],

            'name' => ['required', 'string', 'max:100'],

            'description' => ['nullable', 'string'],

            'is_active' => ['boolean'],

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Open Form (Create)
    |--------------------------------------------------------------------------
    */

    public function createTeam(): void
    {
        $this->resetForm();

        $this->showForm = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Open Form (Edit)
    |--------------------------------------------------------------------------
    */

    public function editTeam(int $teamId): void
    {
        $team = Team::query()

            ->where('department_id', $this->department->id)

            ->where('company_id', Auth::user()->company_id)

            ->findOrFail($teamId);

        $this->editingId = $team->id;
        $this->code = $team->code;
        $this->name = $team->name;
        $this->description = $team->description;
        $this->is_active = (bool) $team->is_active;

        $this->showForm = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Save (Create atau Update)
    |--------------------------------------------------------------------------
    */

    public function save(): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->validate();

        $companyId = Auth::user()->company_id;

        if ($this->editingId) {

            $team = Team::query()
                ->where('department_id', $this->department->id)
                ->where('company_id', $companyId)
                ->findOrFail($this->editingId);

            $team->update([
                'code' => $this->code,
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            $this->successMessage = 'Team berhasil diperbarui.';

        } else {

            Team::create([
                'company_id' => $companyId,
                'department_id' => $this->department->id,
                'code' => $this->code,
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            $this->successMessage = 'Team berhasil ditambahkan.';

        }

        $this->resetForm();
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle Status
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(int $teamId): void
    {
        $team = Team::query()
            ->where('department_id', $this->department->id)
            ->where('company_id', Auth::user()->company_id)
            ->findOrFail($teamId);

        $team->update([
            'is_active' => !$team->is_active,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function deleteTeam(int $teamId): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $team = Team::query()
            ->where('department_id', $this->department->id)
            ->where('company_id', Auth::user()->company_id)
            ->findOrFail($teamId);

        if ($team->employmentHistories()->exists()) {

            $this->errorMessage = 'Team tidak bisa dihapus karena masih digunakan oleh Employee.';

            return;

        }

        $team->delete();

        $this->successMessage = 'Team berhasil dihapus.';
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel / Reset Form
    |--------------------------------------------------------------------------
    */

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
        $teams = Team::query()

            ->where('department_id', $this->department->id)

            ->where('company_id', Auth::user()->company_id)

            ->withCount('employmentHistories')

            ->orderBy('name')

            ->get();

        return view('livewire.department.team-manager', [
            'teams' => $teams,
        ]);
    }
}
