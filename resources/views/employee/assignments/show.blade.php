@extends('layouts.app')

@section('title','Assignment Detail')
@section('page-title','Assignment Detail')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('employee.assignments.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-blue-600">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Kembali ke My Assignment
        </a>

        @if($assignment->daily_attendance_enabled)
            <div class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                <i data-lucide="info" class="h-3.5 w-3.5"></i>
                Check In/Out dilakukan per hari sesuai kalender assignment
            </div>
        @endif
    </div>

    @include('employee.assignments.partials.header')

    <div class="grid items-start gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
        <div class="min-w-0 space-y-6">
            @include('employee.assignments.partials.daily-attendance')
            @include('employee.assignments.partials.description')
            @include('assignment.partials.attachments')
            @include('employee.assignments.partials.location')
            @include('employee.assignments.partials.timeline')
        </div>

        <aside class="space-y-6 xl:sticky xl:top-6">
            @include('employee.assignments.partials.actions')
            @include('employee.assignments.partials.team')
        </aside>
    </div>
</div>
@endsection
