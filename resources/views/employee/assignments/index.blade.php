@extends('layouts.app')

@section('title', 'My Assignment')
@section('page-title', 'My Assignment')

@section('content')
@php
    $total = (int) ($statistics['total'] ?? 0);
    $active = (int) (($statistics['assigned'] ?? 0) + ($statistics['progress'] ?? 0));
    $review = (int) ($statistics['pending_review'] ?? 0);
    $revision = (int) ($statistics['needs_revision'] ?? 0);
    $completed = (int) ($statistics['completed'] ?? 0);
    $hasFilter = request()->hasAny(['search', 'status', 'priority']);
@endphp

<div class="space-y-5">
    {{-- Header --}}
    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm font-bold text-blue-600">
                <i data-lucide="briefcase-business" class="h-4 w-4"></i>
                <span>Employee Workspace</span>
            </div>
            <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">My Assignment</h2>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">Pantau pekerjaan, Daily Attendance, review, dan revisi dari satu halaman.</p>
        </div>
        <div class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-200 bg-white px-3.5 py-2 text-sm font-bold text-slate-700 shadow-sm">
            <i data-lucide="clipboard-list" class="h-4 w-4 text-blue-600"></i>
            <span>{{ $total }} assignment</span>
        </div>
    </div>

    {{-- Summary, intentionally compact like mobile --}}
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-base font-black text-slate-900">Ringkasan Assignment</h3>
                <p class="mt-0.5 text-xs text-slate-500">Status pekerjaan kamu saat ini.</p>
            </div>
            <span class="w-fit rounded-full bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-500">{{ $total }} total</span>
        </div>

        <div class="grid grid-cols-2 divide-x divide-y divide-slate-100 sm:grid-cols-4 sm:divide-y-0">
            @foreach([
                ['label' => 'Aktif', 'value' => $active, 'icon' => 'activity', 'iconClass' => 'bg-blue-50 text-blue-600'],
                ['label' => 'Review', 'value' => $review, 'icon' => 'scan-search', 'iconClass' => 'bg-violet-50 text-violet-600'],
                ['label' => 'Perlu Revisi', 'value' => $revision, 'icon' => 'rotate-ccw', 'iconClass' => 'bg-rose-50 text-rose-600'],
                ['label' => 'Selesai', 'value' => $completed, 'icon' => 'badge-check', 'iconClass' => 'bg-emerald-50 text-emerald-600'],
            ] as $metric)
                <div class="flex items-center gap-3 px-4 py-4 sm:px-5">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $metric['iconClass'] }}">
                        <i data-lucide="{{ $metric['icon'] }}" class="h-5 w-5"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xl font-black leading-none text-slate-900">{{ $metric['value'] }}</div>
                        <div class="mt-1 truncate text-xs font-semibold text-slate-500">{{ $metric['label'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Search and filter --}}
    <section class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-3 flex items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-black text-slate-900">Cari & Filter</h3>
                <p class="mt-0.5 text-xs text-slate-500">Temukan assignment berdasarkan judul, nomor, lokasi, status, atau prioritas.</p>
            </div>
            @if($hasFilter)
                <a href="{{ route('employee.assignments.index') }}" class="inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">
                    <i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i>
                    Reset
                </a>
            @endif
        </div>

        <form method="GET" class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_210px_180px_auto]">
            <label class="relative block">
                <span class="sr-only">Cari assignment</span>
                <i data-lucide="search" class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor, judul, atau lokasi..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50">
            </label>

            <select name="status" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50">
                <option value="">Semua Status</option>
                <option value="Assigned" @selected(request('status') === 'Assigned')>Aktif</option>
                <option value="Accepted" @selected(request('status') === 'Accepted')>Accepted</option>
                <option value="In Progress" @selected(request('status') === 'In Progress')>In Progress</option>
                <option value="Pending Review" @selected(request('status') === 'Pending Review')>Pending Review</option>
                <option value="Needs Revision" @selected(request('status') === 'Needs Revision')>Needs Revision</option>
                <option value="Completed" @selected(request('status') === 'Completed')>Completed</option>
                <option value="Not Worked" @selected(request('status') === 'Not Worked')>Not Worked</option>
                <option value="Cancelled" @selected(request('status') === 'Cancelled')>Cancelled / Rejected</option>
            </select>

            <select name="priority" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50">
                <option value="">Semua Prioritas</option>
                @foreach(['Low', 'Medium', 'High', 'Critical'] as $priority)
                    <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }}</option>
                @endforeach
            </select>

            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
                <i data-lucide="sliders-horizontal" class="h-4 w-4"></i>
                Terapkan
            </button>
        </form>
    </section>

    <div class="flex items-end justify-between gap-4 px-1">
        <div>
            <h3 class="text-base font-black text-slate-900">Daftar Assignment</h3>
            <p class="mt-0.5 text-xs text-slate-500">{{ $assignments->total() }} assignment ditemukan.</p>
        </div>
    </div>

    <div class="grid gap-4 xl:grid-cols-2">
        @forelse($assignments as $assignment)
            @include('employee.assignments.partials.card', ['assignment' => $assignment])
        @empty
            <div class="rounded-3xl border border-dashed border-slate-200 bg-white px-6 py-16 text-center xl:col-span-2">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-300">
                    <i data-lucide="clipboard-x" class="h-7 w-7"></i>
                </div>
                <h3 class="mt-4 font-black text-slate-700">Assignment tidak ditemukan</h3>
                <p class="mx-auto mt-1 max-w-md text-sm leading-6 text-slate-500">
                    {{ $hasFilter ? 'Tidak ada assignment yang cocok dengan filter. Coba ubah atau reset filter.' : 'Belum ada assignment dari company untuk kamu saat ini.' }}
                </p>
                @if($hasFilter)
                    <a href="{{ route('employee.assignments.index') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-50 px-4 py-2.5 text-sm font-bold text-blue-700 transition hover:bg-blue-100">
                        <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                        Reset Filter
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    @if($assignments->hasPages())
        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
            {{ $assignments->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
