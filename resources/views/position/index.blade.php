@extends('layouts.app')

@section('title', 'Position Management')

@section('content')

<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Position Management
            </h1>

            <p class="mt-2 text-slate-500">
                Kelola data position sebagai master data jabatan Employee.
            </p>

        </div>

        <a
            href="{{ route('positions.create') }}"
            class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">

            + Add Position

        </a>

    </div>

    @if(session('success'))

        <div class="rounded-2xl bg-green-100 px-5 py-4 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="rounded-2xl bg-red-100 px-5 py-4 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>

    @endif

    {{-- Statistics --}}
    <div class="grid gap-6 md:grid-cols-3">

        <x-ui.card>
            <p class="text-sm text-slate-500">Total Position</p>
            <h2 class="mt-3 text-4xl font-bold">{{ number_format($statistics['total']) }}</h2>
        </x-ui.card>

        <x-ui.card>
            <p class="text-sm text-slate-500">Active</p>
            <h2 class="mt-3 text-4xl font-bold text-green-600">{{ number_format($statistics['active']) }}</h2>
        </x-ui.card>

        <x-ui.card>
            <p class="text-sm text-slate-500">Inactive</p>
            <h2 class="mt-3 text-4xl font-bold text-red-600">{{ number_format($statistics['inactive']) }}</h2>
        </x-ui.card>

    </div>

    {{-- Filter --}}
    <x-ui.card>

        <form method="GET" class="grid gap-4 md:grid-cols-4">

            <div class="relative md:col-span-2">

                <i data-lucide="search" class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau kode position..."
                    class="w-full rounded-2xl border border-slate-300 py-3 pl-12 pr-4 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

            </div>

            <select
                name="is_active"
                class="rounded-2xl border border-slate-300 px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                <option value="">All Status</option>
                <option value="1" @selected(request('is_active') === '1')>Active</option>
                <option value="0" @selected(request('is_active') === '0')>Inactive</option>

            </select>

            <button
                type="submit"
                class="rounded-2xl bg-blue-600 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                Filter
            </button>

        </form>

    </x-ui.card>

    {{-- Table --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <div class="max-h-[520px] overflow-y-auto overflow-x-auto">

            <table class="min-w-full">

                <thead class="sticky top-0 z-10 bg-slate-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Code</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Description</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Employees</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Action</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200">

                    @forelse($positions as $position)

                        <tr class="transition hover:bg-slate-50">

                            <td class="px-6 py-5 font-semibold text-slate-800">{{ $position->code }}</td>

                            <td class="px-6 py-5">{{ $position->name }}</td>

                            <td class="max-w-xs truncate px-6 py-5 text-slate-500">{{ $position->description ?? '-' }}</td>

                            <td class="px-6 py-5 text-center">{{ $position->employment_histories_count }}</td>

                            <td class="px-6 py-5 text-center">

                                <form
                                    action="{{ route('positions.toggle-status', $position) }}"
                                    method="POST"
                                    onsubmit="return confirm('{{ $position->is_active ? 'Nonaktifkan' : 'Aktifkan' }} position {{ $position->name }}?')">

                                    @csrf
                                    @method('PATCH')

                                    @if($position->is_active)

                                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700 transition hover:bg-green-200">
                                            <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                                            Active
                                        </button>

                                    @else

                                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700 transition hover:bg-red-200">
                                            <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                                            Inactive
                                        </button>

                                    @endif

                                </form>

                            </td>

                            <td class="px-6 py-5">

                                <div class="flex items-center justify-center gap-2">

                                    <a
                                        href="{{ route('positions.edit', $position) }}"
                                        title="Edit Position"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition hover:bg-amber-500 hover:text-white">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </a>

                                    <form
                                        action="{{ route('positions.destroy', $position) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete position {{ $position->name }}?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" title="Delete Position" class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-600 transition hover:bg-red-600 hover:text-white">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="py-20">

                                <div class="flex flex-col items-center justify-center">

                                    <i data-lucide="badge-check" class="mb-4 h-14 w-14 text-slate-300"></i>

                                    <h3 class="text-lg font-semibold text-slate-700">No Position Found</h3>

                                    <p class="mt-2 text-sm text-slate-400">There are no position records available.</p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
            {{ $positions->withQueryString()->links() }}
        </div>

    </div>

</div>

@endsection
