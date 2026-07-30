<?php

namespace App\Livewire\Department;

use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditForm extends Component
{
    public Department $department;

    public string $code = '';

    public string $name = '';

    public ?string $description = null;

    public bool $is_active = true;

    public ?string $successMessage = null;

    public function mount(Department $department): void
    {
        abort_unless(
            $department->company_id === Auth::user()->company_id,
            403
        );

        $this->department = $department;

        $this->code = $department->code;
        $this->name = $department->name;
        $this->description = $department->description;
        $this->is_active = (bool) $department->is_active;
    }

    protected function rules(): array
    {
        return [

            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('departments', 'code')
                    ->where(fn ($query) => $query->where('company_id', $this->department->company_id))
                    ->ignore($this->department->id),
            ],

            'name' => ['required', 'string', 'max:100'],

            'description' => ['nullable', 'string'],

            'is_active' => ['boolean'],

        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->department->update([

            'code' => $this->code,

            'name' => $this->name,

            'description' => $this->description,

            'is_active' => $this->is_active,

        ]);

        $this->dispatch('department-updated');

        $this->successMessage = 'Department berhasil diperbarui.';
        $this->dispatch('action-complete');
        
    }

    public function render()
    {
        return view('livewire.department.edit-form');
    }
}
