@extends('layouts.app')

@section('content')

<div class="space-y-8">

    {{-- Header --}}
    <div>

        <h1 class="text-2xl font-bold text-slate-800">
            Dashboard
        </h1>

        <p class="mt-2 text-slate-500">

            Selamat datang kembali,

            <strong>

                {{ auth()->user()->employee->full_name }}

            </strong>

        </p>

    </div>

    {{-- Statistics --}}
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">

        <x-dashboard.stat-card
            title="Employee"
            :value="$total_employee"
            icon="users"/>

        <x-dashboard.stat-card
            title="Attendance"
            :value="$attendance_today"
            icon="calendar-check"/>

        <x-dashboard.stat-card
            title="Late"
            :value="$late_today"
            icon="clock-3"/>

        <x-dashboard.stat-card
            title="Assignment"
            :value="$active_assignment"
            icon="clipboard-list"/>

    </div>

    {{-- Chart --}}
    <x-dashboard.attendance-chart
        :labels="$attendance_chart['labels'] ?? []"
        :data="$attendance_chart['data'] ?? []"/>

</div>

@endsection