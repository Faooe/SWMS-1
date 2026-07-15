@extends('layouts.app')

@section('title','Employee Dashboard')

@section('page-title','Dashboard')

@section('content')

<div class="space-y-8">

    {{-- Greeting --}}
    <x-employee.dashboard.greeting
        :employee="$employee" />

    {{-- Statistics --}}
    <x-employee.dashboard.statistics
        :statistics="$statistics" />

    <div class="grid gap-8 xl:grid-cols-3">

        <div class="space-y-8 xl:col-span-2">

            {{-- Today's Assignment --}}
            <x-employee.dashboard.today-assignment
                :assignment="$todayAssignment" />

            {{-- Recent Activity --}}
            <x-employee.dashboard.recent-activity
                :activities="$recentActivities" />

        </div>

        <div class="space-y-8">

            {{-- Attendance --}}
            <x-employee.dashboard.attendance
                :attendance="$todayAttendance" />

            {{-- Quick Action --}}
            <x-employee.dashboard.quick-action />

        </div>

    </div>

</div>

@endsection