@extends('layouts.app')

@section('title', 'My Assignment')
@section('page-title', 'My Assignment')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-black text-slate-900">Assignment Saya</h2>
            <p class="mt-1 text-sm text-slate-500">Pantau pekerjaan, attendance harian, review, dan revisi dalam satu tempat.</p>
        </div>
        <span class="w-fit rounded-full bg-blue-100 px-4 py-2 text-sm font-bold text-blue-700">{{ $assignments->total() }} Assignment</span>
    </div>

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-5">
        @php
            $summaryCards = [
                ['label' => 'Total', 'value' => $statistics['total'] ?? 0, 'icon' => 'clipboard-list', 'class' => 'bg-slate-50 text-slate-700'],
                ['label' => 'Aktif', 'value' => ($statistics['assigned'] ?? 0) + ($statistics['progress'] ?? 0), 'icon' => 'loader-circle', 'class' => 'bg-blue-50 text-blue-700'],
                ['label' => 'Review', 'value' => $statistics['pending_review'] ?? 0, 'icon' => 'scan-search', 'class' => 'bg-violet-50 text-violet-700'],
                ['label' => 'Perlu Revisi', 'value' => $statistics['needs_revision'] ?? 0, 'icon' => 'rotate-ccw', 'class' => 'bg-rose-50 text-rose-700'],
                ['label' => 'Selesai', 'value' => $statistics['completed'] ?? 0, 'icon' => 'badge-check', 'class' => 'bg-emerald-50 text-emerald-700'],
            ];
        @endphp

        @foreach($summaryCards as $item)
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $item['label'] }}</p>
                        <p class="mt-1 text-2xl font-black text-slate-900">{{ $item['value'] }}</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $item['class'] }}">
                        <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <form method="GET" class="grid gap-3 md:grid-cols-[minmax(0,1fr)_190px_170px_auto]">
            <div class="relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, nomor, atau lokasi..." class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50">
            </div>

            <select name="status" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50">
                <option value="">Semua Status</option>
                <option value="Assigned" @selected(request('status') === 'Assigned')>Aktif / Belum Submit</option>
                <option value="Accepted" @selected(request('status') === 'Accepted')>Accepted</option>
                <option value="In Progress" @selected(request('status') === 'In Progress')>In Progress</option>
                <option value="Pending Review" @selected(request('status') === 'Pending Review')>Pending Review</option>
                <option value="Needs Revision" @selected(request('status') === 'Needs Revision')>Needs Revision</option>
                <option value="Completed" @selected(request('status') === 'Completed')>Completed</option>
                <option value="Not Worked" @selected(request('status') === 'Not Worked')>Not Worked</option>
                <option value="Cancelled" @selected(request('status') === 'Cancelled')>Cancelled / Rejected</option>
            </select>

            <select name="priority" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50">
                <option value="">Semua Prioritas</option>
                @foreach(['Low', 'Medium', 'High', 'Critical'] as $priority)
                    <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }}</option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700">
                    <i data-lucide="sliders-horizontal" class="h-4 w-4"></i>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'priority']))
                    <a href="{{ route('employee.assignments.index') }}" class="flex items-center justify-center rounded-2xl border border-slate-200 px-4 text-slate-500 transition hover:bg-slate-50" title="Reset filter">
                        <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="grid gap-5 xl:grid-cols-2">
        @forelse($assignments as $assignment)
            @include('employee.assignments.partials.card', ['assignment' => $assignment])
        @empty
            <div class="rounded-3xl border border-dashed border-slate-200 bg-white px-6 py-16 text-center xl:col-span-2">
                <i data-lucide="clipboard-x" class="mx-auto h-12 w-12 text-slate-300"></i>
                <h3 class="mt-4 font-bold text-slate-700">Assignment tidak ditemukan</h3>
                <p class="mt-1 text-sm text-slate-500">Coba ubah filter atau tunggu assignment baru dari company.</p>
            </div>
        @endforelse
    </div>

    @if($assignments->hasPages())
        <div>{{ $assignments->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection
