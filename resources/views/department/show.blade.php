@extends('layouts.app')

@section('title', 'Department Detail')

@section('content')

<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="flex items-center gap-3">

                <h1 class="text-3xl font-bold text-slate-800">
                    {{ $department->name }}
                </h1>

                @if($department->is_active)

                    <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700">
                        <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                        Active
                    </span>

                @else

                    <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700">
                        <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                        Inactive
                    </span>

                @endif

            </div>

            <p class="mt-2 text-slate-500">
                {{ $department->code }} &middot; {{ $department->description ?? 'Tidak ada deskripsi.' }}
            </p>

        </div>

        <div class="flex items-center gap-2">

            <a
                href="{{ route('departments.edit', $department) }}"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-semibold text-slate-700 hover:bg-slate-50">

                Edit Department

            </a>

            <a
                href="{{ route('departments.index') }}"
                class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">

                &larr; Kembali

            </a>

        </div>

    </div>

    {{-- Statistics --}}
    <div class="grid gap-6 md:grid-cols-2">

        <x-ui.card>
            <p class="text-sm text-slate-500">Total Employee</p>
            <h2 class="mt-3 text-4xl font-bold">{{ number_format($employmentHistories->count()) }}</h2>
        </x-ui.card>

        <x-ui.card>
            <p class="text-sm text-slate-500">Total Team</p>
            <h2 class="mt-3 text-4xl font-bold">{{ number_format($department->teams->count()) }}</h2>
        </x-ui.card>

    </div>

    {{-- Teams in this department --}}
    @if($department->teams->isNotEmpty())

        <x-ui.card>

            <h3 class="mb-4 text-lg font-semibold text-slate-800">Team pada Department ini</h3>

            <div class="flex flex-wrap gap-2">

                @foreach($department->teams as $team)

                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700">

                        {{ $team->name }}

                        @unless($team->is_active)
                            <span class="text-xs font-semibold text-red-500">(Inactive)</span>
                        @endunless

                    </span>

                @endforeach

            </div>

        </x-ui.card>

    @endif

    {{-- Employees in this department --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-800">Employee di Department ini</h3>
        </div>

        <div class="max-h-[560px] overflow-y-auto overflow-x-auto">

            <table class="min-w-full">

                <thead class="sticky top-0 z-10 bg-slate-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Employee</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Position</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Team</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200">

                    @forelse($employmentHistories as $history)

                        <tr class="transition hover:bg-slate-50">

                            <td class="px-6 py-5">

                                <a
                                    href="{{ route('employees.show', $history->employee) }}"
                                    class="flex items-center gap-4">

                                    <x-ui.avatar
                                        :employee="$history->employee"
                                        size="12" />

                                    <div>

                                        <h3 class="font-semibold text-slate-800">
                                            {{ $history->employee?->full_name ?? '-' }}
                                        </h3>

                                        <p class="text-sm text-slate-500">
                                            {{ $history->employee?->email ?? '-' }}
                                        </p>

                                    </div>

                                </a>

                            </td>

                            <td class="px-6 py-5">
                                <span class="font-medium">{{ $history->position?->name ?? '-' }}</span>
                            </td>

                            <td class="px-6 py-5">
                                <span class="font-medium">{{ $history->team?->name ?? '-' }}</span>
                            </td>

                            <td class="px-6 py-5 text-center">

                                @if($history->employee?->is_active)

                                    <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700">
                                        <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                                        Active
                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700">
                                        <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                                        Inactive
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="py-20">

                                <div class="flex flex-col items-center justify-center">

                                    <i data-lucide="users" class="mb-4 h-14 w-14 text-slate-300"></i>

                                    <h3 class="text-lg font-semibold text-slate-700">Belum Ada Employee</h3>

                                    <p class="mt-2 text-sm text-slate-400">Belum ada employee yang ditempatkan di department ini.</p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
