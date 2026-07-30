<?php

namespace App\Http\Requests\Assignment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'title' => ['required', 'string', 'max:150'],

            'description' => ['nullable', 'string'],

            'office_id' => ['required', 'integer', 'exists:offices,id'],

            'location_name' => ['required', 'string', 'max:150'],

            'address' => ['nullable', 'string'],

            'latitude' => ['required', 'numeric', 'between:-90,90'],

            'longitude' => ['required', 'numeric', 'between:-180,180'],

            'radius' => ['required', 'integer', 'min:0'],

            'polygon' => ['nullable', 'string'],

            'priority' => ['required', 'in:Low,Medium,High,Critical'],

            'assignment_type' => ['required', 'in:Maintenance,Installation,Inspection,Survey,Emergency'],

            'status' => ['required', 'in:Draft,Assigned,In Progress,Completed,Cancelled'],

            'start_datetime' => ['required', 'date'],

            'end_datetime' => ['required', 'date', 'after:start_datetime'],

            'employees' => ['nullable', 'array'],

            'employees.*' => ['integer', 'exists:employees,id'],

        ];
    }
}