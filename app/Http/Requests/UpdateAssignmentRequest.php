<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssignmentRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Assignment
            |--------------------------------------------------------------------------
            */

            'title' => [

                'required',

                'string',

                'max:150',

            ],

            'description' => [

                'nullable',

                'string',

            ],

            /*
            |--------------------------------------------------------------------------
            | Office
            |--------------------------------------------------------------------------
            */

            'office_id' => [

                'required',

                'exists:offices,id',

            ],

            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            'location_name' => [

                'required',

                'string',

                'max:150',

            ],

            'address' => [

                'nullable',

                'string',

            ],

            'latitude' => [

                'required',

                'numeric',

                'between:-90,90',

            ],

            'longitude' => [

                'required',

                'numeric',

                'between:-180,180',

            ],

            'radius' => [

                'required',

                'integer',

                'min:50',

                'max:1000',

            ],

            /*
            |--------------------------------------------------------------------------
            | Assignment
            |--------------------------------------------------------------------------
            */

            'priority' => [

                'required',

                Rule::in([

                    'Low',

                    'Medium',

                    'High',

                    'Critical',

                ]),

            ],

            'assignment_type' => [

                'required',

                Rule::in([

                    'Maintenance',

                    'Installation',

                    'Inspection',

                    'Survey',

                    'Emergency',

                ]),

            ],

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            |
            | "In Progress" dan "Completed" tidak lagi dipilih manual dari form
            | edit (lihat komponen assignment-information), tapi tetap diterima
            | di sini karena nilai tersebut ikut terkirim lewat hidden input saat
            | assignment sedang berjalan / sudah selesai secara otomatis.
            | Validasi tambahan terhadap percobaan mengubah status secara manual
            | ke "In Progress"/"Completed" dilakukan di AssignmentService::update().
            |
            */

            'status' => [

                'required',

                Rule::in([

                    'Draft',

                    'Assigned',

                    'In Progress',

                    'Completed',

                    'Cancelled',

                ]),

            ],

            /*
            |--------------------------------------------------------------------------
            | Schedule
            |--------------------------------------------------------------------------
            */

            'start_datetime' => [

                'required',

                'date',

            ],

            'end_datetime' => [

                'required',

                'date',

                'after:start_datetime',

            ],

            /*
            |--------------------------------------------------------------------------
            | Employees
            |--------------------------------------------------------------------------
            */

            'employees' => [

                'required',

                'array',

                'min:1',

            ],

            'employees.*' => [

                'exists:employees,id',

            ],

        ];
    }
}