@extends('layouts.app')

@section('title','Employee Detail')

@section('page-title','Employee Detail')

@section('content')

<div class="space-y-8">

    {{-- ================= Header ================= --}}
    <div
        class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

        <div
            class="flex flex-col justify-between gap-6 lg:flex-row lg:items-center">

            <div class="flex items-center gap-6">

                <div
                    <x-ui.avatar:employee="$employee"size="24"/>
                </div>

                <div>

                    <h1
                        class="text-3xl font-bold text-slate-800">

                        {{ $employee->full_name }}

                    </h1>

                    <p class="mt-2 text-slate-500">

                        Employee Number :
                        <strong>{{ $employee->employee_number }}</strong>

                    </p>

                    <div class="mt-4">

                        @if($employee->is_active)

                            <span
                                class="rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">

                                Active

                            </span>

                        @else

                            <span
                                class="rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-700">

                                Inactive

                            </span>

                        @endif

                    </div>

                </div>

            </div>

            <div class="flex gap-3">

                <a
                    href="{{ route('employees.edit',$employee) }}"
                    class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700">

                    Edit Employee

                </a>

                <a
                    href="{{ route('employees.index') }}"
                    class="rounded-xl border border-slate-300 px-6 py-3 font-semibold hover:bg-slate-100">

                    Back

                </a>

            </div>

        </div>

    </div>

    {{-- ================= Personal ================= --}}
    <div
        class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

        <h2
            class="mb-8 text-xl font-bold">

            Personal Information

        </h2>

        <div class="grid gap-6 md:grid-cols-2">

            <x-ui.detail-item
                label="Employee Number"
                :value="$employee->employee_number"/>

            <x-ui.detail-item
                label="Full Name"
                :value="$employee->full_name"/>

            <x-ui.detail-item
                label="Email"
                :value="$employee->email"/>

            <x-ui.detail-item
                label="Phone"
                :value="$employee->phone"/>

            <x-ui.detail-item
                label="Gender"
                :value="$employee->gender"/>

            <x-ui.detail-item
                label="Birth Place"
                :value="$employee->birth_place"/>

            <x-ui.detail-item
                label="Birth Date"
                :value="optional($employee->birth_date)->format('d M Y')"/>

            <x-ui.detail-item
                label="Identity Number"
                :value="$employee->identity_number"/>

            <x-ui.detail-item
                label="Marital Status"
                :value="$employee->marital_status"/>

            <x-ui.detail-item
                label="Emergency Contact"
                :value="$employee->emergency_contact_name"/>

            <x-ui.detail-item
                label="Emergency Phone"
                :value="$employee->emergency_contact_phone"/>

        </div>

        <div class="mt-6">

            <label
                class="mb-2 block text-sm font-semibold text-slate-500">

                Address

            </label>

            <div
                class="rounded-2xl border border-slate-200 bg-slate-50 p-4">

                {{ $employee->address ?: '-' }}

            </div>

        </div>

    </div>

    {{-- ================= Employment ================= --}}
    <div
        class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

        <h2
            class="mb-8 text-xl font-bold">

            Employment Information

        </h2>

        <div class="grid gap-6 md:grid-cols-2">

            <x-ui.detail-item
                label="Office"
                :value="$employee->currentEmployment?->office?->name"/>

            <x-ui.detail-item
                label="Department"
                :value="$employee->currentEmployment?->department?->name"/>

            <x-ui.detail-item
                label="Position"
                :value="$employee->currentEmployment?->position?->name"/>

            <x-ui.detail-item
                label="Team"
                :value="$employee->currentEmployment?->team?->name"/>

            <x-ui.detail-item
                label="Supervisor"
                :value="$employee->currentEmployment?->supervisor?->full_name"/>

            <x-ui.detail-item
                label="Employment Type"
                :value="$employee->currentEmployment?->employment_type"/>

            <x-ui.detail-item
                label="Employment Status"
                :value="$employee->currentEmployment?->employment_status"/>

            <x-ui.detail-item
                label="Start Date"
                :value="optional($employee->currentEmployment?->start_date)->format('d M Y')"/>

            <x-ui.detail-item
                label="End Date"
                :value="optional($employee->currentEmployment?->end_date)->format('d M Y')"/>

            <x-ui.detail-item
                label="Current Employment"
                :value="$employee->currentEmployment?->is_current ? 'Yes' : 'No'"/>

        </div>

    </div>

</div>

@endsection