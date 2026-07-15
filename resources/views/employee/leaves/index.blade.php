@extends('layouts.app')

@section('title', 'Leave / Permission')

@section('page-title', 'Leave / Permission')

@section('content')

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div>

        <h1 class="text-2xl font-bold text-slate-800">

            Leave / Permission

        </h1>

        <p class="mt-1 text-sm text-slate-500">

            Ajukan izin (Sakit / Acara) maksimal 3 hari. Setelah disetujui admin,
            kamu tidak akan tercatat Absent pada tanggal tersebut.

        </p>

    </div>

    @if(session('success'))

        <div class="rounded-2xl bg-green-100 px-5 py-4 text-sm font-medium text-green-700">

            {{ session('success') }}

        </div>

    @endif

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- ========================================================= --}}
        {{-- Form --}}
        {{-- ========================================================= --}}

        <x-ui.card class="lg:col-span-1">

            <h3 class="mb-5 text-lg font-bold text-slate-800">

                Ajukan Izin Baru

            </h3>

            <form

                method="POST"

                action="{{ route('employee.leaves.store') }}"

                class="space-y-5">

                @csrf

                <x-ui.select
                    name="type"
                    label="Jenis Izin"
                    :options="['Sakit' => 'Sakit', 'Acara' => 'Acara / Keperluan Pribadi']"
                    placeholder="Pilih Jenis Izin"
                    required />

                <x-ui.input
                    name="start_date"
                    type="date"
                    label="Tanggal Mulai"
                    required />

                <x-ui.input
                    name="end_date"
                    type="date"
                    label="Tanggal Selesai"
                    required />

                <x-ui.textarea
                    name="reason"
                    label="Alasan"
                    placeholder="Jelaskan alasan izin kamu..."
                    required />

                <button

                    type="submit"

                    class="w-full rounded-2xl bg-blue-600 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">

                    Kirim Pengajuan

                </button>

            </form>

        </x-ui.card>

        {{-- ========================================================= --}}
        {{-- History --}}
        {{-- ========================================================= --}}

        <div class="space-y-4 lg:col-span-2">

            @forelse($leaves as $leave)

                <x-ui.card>

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="font-bold text-slate-800">

                                {{ $leave->type }}

                            </p>

                            <p class="mt-1 text-sm text-slate-500">

                                {{ $leave->start_date->format('d M Y') }}
                                &mdash;
                                {{ $leave->end_date->format('d M Y') }}
                                ({{ $leave->duration }} hari)

                            </p>

                            <p class="mt-2 text-sm text-slate-600">

                                {{ $leave->reason }}

                            </p>

                            @if($leave->isRejected() && $leave->rejection_reason)

                                <p class="mt-2 text-sm text-red-600">

                                    Alasan ditolak: {{ $leave->rejection_reason }}

                                </p>

                            @endif

                        </div>

                        <x-ui.badge :color="match($leave->status) {
                            'Approved' => 'green',
                            'Rejected' => 'red',
                            default => 'yellow',
                        }">

                            {{ $leave->status }}

                        </x-ui.badge>

                    </div>

                </x-ui.card>

            @empty

                <x-ui.card>

                    <div class="py-16 text-center">

                        <i

                            data-lucide="file-text"

                            class="mx-auto h-12 w-12 text-slate-300">

                        </i>

                        <h3 class="mt-5 text-lg font-bold text-slate-800">

                            Belum Ada Pengajuan Izin

                        </h3>

                        <p class="mt-2 text-sm text-slate-500">

                            Riwayat pengajuan izin kamu akan muncul di sini.

                        </p>

                    </div>

                </x-ui.card>

            @endforelse

            @if($leaves->hasPages())

                <div>

                    {{ $leaves->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
