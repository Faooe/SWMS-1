@extends('layouts.app')

@section('title','Assignment')
@section('page-title','Assignment Management')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Workforce Assignment</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Kelola Assignment</h1>
            <p class="mt-1 text-sm text-slate-500">Pantau pekerjaan, review hasil employee, Daily Attendance, dan revisi dari satu tempat.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('assignment-settings.edit') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                <i data-lucide="settings" class="h-4 w-4"></i>
                Pengaturan
            </a>
            <a href="{{ route('assignments.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Buat Assignment
            </a>
        </div>
    </div>

    <x-assignment.statistics.overview :statistics="$statistics" />

    <x-assignment.filters.search :offices="$offices" />

    <div>
        <div class="mb-3 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Daftar Assignment</h2>
                <p class="mt-0.5 text-sm text-slate-500">{{ $assignments->total() }} assignment ditemukan.</p>
            </div>
        </div>
        <x-assignment.table.table :assignments="$assignments" />
    </div>
</div>
@endsection
