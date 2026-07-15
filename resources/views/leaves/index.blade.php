@extends('layouts.app')

@section('title', 'Leave / Permission Management')

@section('content')

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div>

        <h1 class="text-3xl font-bold text-slate-800">

            Leave / Permission Management

        </h1>

        <p class="mt-2 text-slate-500">

            Review dan setujui pengajuan izin karyawan. Izin yang disetujui otomatis
            tercatat sebagai attendance status Permission dan aman dari auto-absent.

        </p>

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

                    placeholder="Cari nama karyawan..."

                    class="w-full rounded-2xl border border-slate-300 py-3 pl-12 pr-4 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

            </div>

            <select

                name="status"

                class="rounded-2xl border border-slate-300 px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                <option value="">All Status</option>

                <option value="Pending" @selected(request('status') == 'Pending')>Pending</option>

                <option value="Approved" @selected(request('status') == 'Approved')>Approved</option>

                <option value="Rejected" @selected(request('status') == 'Rejected')>Rejected</option>

            </select>

            <button

                type="submit"

                class="rounded-2xl bg-blue-600 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">

                Filter

            </button>

        </form>

    </x-ui.card>

    {{-- ========================================================= --}}
    {{-- Table --}}
    {{-- ========================================================= --}}

    <x-ui.card class="overflow-hidden p-0">

        <div class="max-h-[520px] overflow-y-auto overflow-x-auto">

        <table class="w-full text-left text-sm">

            <thead class="sticky top-0 z-10 border-b border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">

                <tr>

                    <th class="px-6 py-4">Employee</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Period</th>
                    <th class="px-6 py-4">Reason</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Action</th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse($leaves as $leave)

                    <tr>

                        <td class="px-6 py-4 font-medium text-slate-800">

                            {{ $leave->employee->full_name }}

                        </td>

                        <td class="px-6 py-4 text-slate-600">

                            {{ $leave->type }}

                        </td>

                        <td class="px-6 py-4 text-slate-600">

                            {{ $leave->start_date->format('d M Y') }}
                            &mdash;
                            {{ $leave->end_date->format('d M Y') }}
                            <span class="text-xs text-slate-400">
                                ({{ $leave->duration }} hari)
                            </span>

                        </td>

                        <td class="max-w-xs truncate px-6 py-4 text-slate-600">

                            {{ $leave->reason }}

                        </td>

                        <td class="px-6 py-4">

                            <x-ui.badge :color="match($leave->status) {
                                'Approved' => 'green',
                                'Rejected' => 'red',
                                default => 'yellow',
                            }">

                                {{ $leave->status }}

                            </x-ui.badge>

                        </td>

                        <td class="px-6 py-4">

                            @if($leave->canBeReviewed())

                                <div class="flex items-center justify-end gap-2">

                                    <form

                                        method="POST"

                                        action="{{ route('leaves.approve', $leave) }}">

                                        @csrf
                                        @method('PATCH')

                                        <button

                                            type="submit"

                                            class="rounded-xl bg-green-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-green-700">

                                            Approve

                                        </button>

                                    </form>

                                    <form

                                        method="POST"

                                        action="{{ route('leaves.reject', $leave) }}">

                                        @csrf
                                        @method('PATCH')

                                        <button

                                            type="submit"

                                            class="rounded-xl bg-red-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-red-700">

                                            Reject

                                        </button>

                                    </form>

                                </div>

                            @else

                                <span class="block text-right text-xs text-slate-400">

                                    Reviewed by {{ $leave->approver?->name ?? '-' }}

                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="px-6 py-16 text-center text-slate-500">

                            Belum ada pengajuan izin.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

        </div>

    </x-ui.card>

    @if($leaves->hasPages())

        <div>

            {{ $leaves->appends(request()->query())->links() }}

        </div>

    @endif

</div>

@endsection
