@extends('layouts.app')

@section('title', 'My Assignment')

@section('page-title', 'My Assignment')

@section('content')

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                My Assignment

            </h1>

            <p class="mt-1 text-sm text-slate-500">

                Lihat dan kelola semua penugasan kamu.

            </p>

        </div>

        <span class="rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">

            {{ $assignments->total() }} Assignment

        </span>

    </div>

    {{-- ========================================================= --}}
    {{-- Filter --}}
    {{-- ========================================================= --}}

    <x-ui.card>

        <form

            method="GET"

            class="grid gap-4 md:grid-cols-4">

            <div class="relative md:col-span-2">

                <i

                    data-lucide="search"

                    class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400">

                </i>

                <input

                    type="text"

                    name="search"

                    value="{{ request('search') }}"

                    placeholder="Cari assignment..."

                    class="w-full rounded-2xl border border-slate-300 py-3 pl-12 pr-4 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

            </div>

            <select

                name="status"

                class="rounded-2xl border border-slate-300 px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                <option value="">All Status</option>

                <option

                    value="Assigned"

                    @selected(request('status') == 'Assigned')>

                    Assigned

                </option>

                <option

                    value="In Progress"

                    @selected(request('status') == 'In Progress')>

                    In Progress

                </option>

                <option

                    value="Completed"

                    @selected(request('status') == 'Completed')>

                    Completed

                </option>

                <option

                    value="Cancelled"

                    @selected(request('status') == 'Cancelled')>

                    Cancelled

                </option>

            </select>

            <button

                type="submit"

                class="rounded-2xl bg-blue-600 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">

                Filter

            </button>

        </form>

    </x-ui.card>

    {{-- ========================================================= --}}
    {{-- Assignment Cards --}}
    {{-- ========================================================= --}}

    <div class="grid gap-5">

        @forelse($assignments as $assignment)

            @include('employee.assignments.partials.card', [

                'assignment' => $assignment,

            ])

        @empty

            <x-ui.card>

                <div class="py-16 text-center">

                    <i

                        data-lucide="clipboard-list"

                        class="mx-auto h-12 w-12 text-slate-300">

                    </i>

                    <h3 class="mt-5 text-lg font-bold text-slate-800">

                        No Assignment

                    </h3>

                    <p class="mt-2 text-sm text-slate-500">

                        Kamu belum memiliki assignment apapun.

                    </p>

                </div>

            </x-ui.card>

        @endforelse

    </div>

    {{-- ========================================================= --}}
    {{-- Pagination --}}
    {{-- ========================================================= --}}

    @if($assignments->hasPages())

        <div>

            {{ $assignments->appends(request()->query())->links() }}

        </div>

    @endif

</div>

@endsection