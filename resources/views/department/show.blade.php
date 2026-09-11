@extends('layouts.app')

@section('title', 'Detail Department')
@section('page-title', 'Department')

@section('content')
<div class="space-y-5">
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-5 px-5 py-5 sm:px-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="flex min-w-0 items-start gap-4">
                <a href="{{ route('departments.index') }}" title="Kembali ke daftar department" class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition hover:bg-slate-200"><i data-lucide="arrow-left" class="h-5 w-5"></i></a>
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600"><i data-lucide="network" class="h-7 w-7"></i></div>
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-[0.1em] text-blue-600"><span>Detail Department</span><span class="text-slate-300">•</span><span class="text-slate-400">{{ $department->code }}</span></div>
                    <h1 class="mt-1 truncate text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">{{ $department->name }}</h1>
                    <div class="mt-3 flex flex-wrap items-center gap-2"><span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-bold {{ $department->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }}"><span class="h-1.5 w-1.5 rounded-full {{ $department->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $department->is_active ? 'Aktif' : 'Nonaktif' }}</span></div>
                </div>
            </div>
            <a href="{{ route('departments.edit', $department) }}" class="inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100"><i data-lucide="square-pen" class="h-4 w-4"></i>Edit Department</a>
        </div>

        <div class="grid grid-cols-2 divide-x divide-y divide-slate-100 border-t border-slate-100 sm:grid-cols-4 sm:divide-y-0">
            @foreach([
                ['Employee', $employmentHistories->total(), 'Penempatan aktif', 'users', 'bg-blue-50 text-blue-600'],
                ['Team', $department->teams->count(), 'Total team', 'users-round', 'bg-violet-50 text-violet-600'],
                ['Team Aktif', $department->teams->where('is_active', true)->count(), 'Siap digunakan', 'circle-check', 'bg-emerald-50 text-emerald-600'],
                ['Kode', $department->code, 'Identitas department', 'hash', 'bg-amber-50 text-amber-600'],
            ] as [$label, $value, $caption, $icon, $tone])
                <div class="flex min-w-0 items-center gap-3 px-4 py-4 sm:px-5"><div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $tone }}"><i data-lucide="{{ $icon }}" class="h-5 w-5"></i></div><div class="min-w-0"><p class="truncate text-lg font-black leading-none text-slate-900">{{ $value }}</p><p class="mt-1 text-xs font-bold text-slate-500">{{ $label }}</p><p class="mt-0.5 truncate text-[11px] text-slate-400">{{ $caption }}</p></div></div>
            @endforeach
        </div>
    </section>

    <div class="grid items-start gap-5 xl:grid-cols-[minmax(0,1fr)_360px]">
        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
                <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="users" class="h-5 w-5"></i></span><div><h2 class="text-base font-black text-slate-900">Employee Department</h2><p class="mt-0.5 text-xs text-slate-500">Employee dengan penempatan aktif di department ini.</p></div></div>
                <span class="shrink-0 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-500">{{ $employmentHistories->total() }} employee</span>
            </div>

            <div class="divide-y divide-slate-100 md:hidden">
                @forelse($employmentHistories as $history)
                    <a href="{{ route('employees.show', $history->employee) }}" class="flex items-center gap-3 p-4 transition hover:bg-slate-50">
                        <x-ui.avatar :employee="$history->employee" size="11" />
                        <div class="min-w-0 flex-1"><p class="truncate text-sm font-bold text-slate-900">{{ $history->employee?->full_name ?? '-' }}</p><p class="mt-0.5 truncate text-xs text-slate-400">{{ $history->position?->name ?? 'Position belum diatur' }} · {{ $history->team?->name ?? 'Tanpa team' }}</p></div>
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $history->employee?->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }}"><span class="h-1.5 w-1.5 rounded-full {{ $history->employee?->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $history->employee?->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                    </a>
                @empty
                    <div class="px-6 py-16 text-center"><div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-300"><i data-lucide="users" class="h-7 w-7"></i></div><h3 class="mt-4 font-black text-slate-700">Belum ada employee</h3><p class="mx-auto mt-1 max-w-md text-sm leading-6 text-slate-500">Employee yang ditempatkan pada department ini akan muncul di sini.</p></div>
                @endforelse
            </div>

            <div class="hidden overflow-x-auto md:block">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/80"><tr><th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Employee</th><th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Position</th><th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Team</th><th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Status</th><th class="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($employmentHistories as $history)
                            <tr class="group transition hover:bg-slate-50/70">
                                <td class="px-5 py-4"><a href="{{ route('employees.show', $history->employee) }}" class="flex min-w-56 items-center gap-3"><x-ui.avatar :employee="$history->employee" size="11" /><div class="min-w-0"><p class="truncate text-sm font-bold text-slate-900 group-hover:text-blue-700">{{ $history->employee?->full_name ?? '-' }}</p><p class="mt-0.5 truncate text-xs text-slate-400">{{ $history->employee?->email ?? '-' }}</p></div></a></td>
                                <td class="px-5 py-4 text-sm font-semibold text-slate-700">{{ $history->position?->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-sm font-semibold text-slate-700">{{ $history->team?->name ?? '-' }}</td>
                                <td class="px-5 py-4"><span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $history->employee?->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }}"><span class="h-1.5 w-1.5 rounded-full {{ $history->employee?->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $history->employee?->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td class="px-5 py-4 text-right"><a href="{{ route('employees.show', $history->employee) }}" title="Detail employee" class="ml-auto flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-blue-50 hover:text-blue-600"><i data-lucide="eye" class="h-4 w-4"></i></a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-16 text-center"><div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-300"><i data-lucide="users" class="h-7 w-7"></i></div><h3 class="mt-4 font-black text-slate-700">Belum ada employee</h3><p class="mt-1 text-sm text-slate-500">Employee yang ditempatkan pada department ini akan muncul di sini.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($employmentHistories->hasPages())<div class="border-t border-slate-100 px-5 py-3">{{ $employmentHistories->links() }}</div>@endif
        </section>

        <aside class="space-y-5 xl:sticky xl:top-6">
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4"><div><h2 class="text-sm font-black text-slate-900">Team Department</h2><p class="mt-0.5 text-xs text-slate-500">Unit kerja di dalam department.</p></div><a href="{{ route('departments.edit', $department) }}" class="inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold text-blue-600 transition hover:bg-blue-50"><i data-lucide="settings-2" class="h-3.5 w-3.5"></i>Kelola</a></div>
                <div class="p-5">
                    @forelse($department->teams as $team)
                        <div class="mb-2 flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-3 last:mb-0"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-violet-600 shadow-sm"><i data-lucide="users-round" class="h-4 w-4"></i></span><div class="min-w-0 flex-1"><p class="truncate text-sm font-bold text-slate-800">{{ $team->name }}</p><p class="mt-0.5 text-xs text-slate-400">{{ $team->code }}</p></div><span class="h-2 w-2 shrink-0 rounded-full {{ $team->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}" title="{{ $team->is_active ? 'Aktif' : 'Nonaktif' }}"></span></div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 px-4 py-8 text-center"><i data-lucide="users-round" class="mx-auto h-6 w-6 text-slate-300"></i><p class="mt-2 text-sm font-bold text-slate-600">Belum ada team</p><p class="mt-1 text-xs text-slate-400">Tambahkan team melalui halaman edit.</p></div>
                    @endforelse
                </div>
            </section>

            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"><div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i data-lucide="file-text" class="h-5 w-5"></i></span><div><h2 class="text-sm font-black text-slate-900">Deskripsi</h2><p class="mt-0.5 text-xs text-slate-500">Keterangan department.</p></div></div><p class="p-5 text-sm leading-6 text-slate-600">{{ $department->description ?: 'Belum ada deskripsi untuk department ini.' }}</p></section>
        </aside>
    </div>
</div>
@endsection
