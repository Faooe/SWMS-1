@extends('layouts.app')

@section('title', 'Work Calendar')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Work Calendar & Hari Libur</h1>
            <p class="mt-1 text-sm text-slate-500">Atur hari kerja company dan tanggal libur yang harus dilewati oleh Auto Absent.</p>
        </div>
        <a href="{{ route('attendance.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">← Kembali ke Attendance</a>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
    @endif

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5">
            <h2 class="text-lg font-bold text-slate-900">Hari Kerja Mingguan</h2>
            <p class="text-sm text-slate-500">Default Senin–Jumat. Hari yang tidak dicentang tidak akan menghasilkan Auto Absent.</p>
        </div>
        @php
            $dayLabels = [
                'monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu',
                'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'
            ];
        @endphp
        <form method="POST" action="{{ route('attendance.calendar.schedule') }}">
            @csrf
            @method('PUT')
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-7">
                @foreach($dayLabels as $field => $label)
                    <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 px-4 py-4 hover:bg-slate-50">
                        <input type="checkbox" name="{{ $field }}" value="1" @checked($schedule->{$field}) class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="font-semibold text-slate-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <button class="mt-5 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-700">Simpan Hari Kerja</button>
        </form>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5">
            <h2 class="text-lg font-bold text-slate-900">Tambah Hari Libur</h2>
            <p class="text-sm text-slate-500">Gunakan untuk Libur Nasional, Cuti Bersama, atau Libur khusus Company.</p>
        </div>
        <form method="POST" action="{{ route('attendance.calendar.holidays.store') }}" class="grid gap-4 lg:grid-cols-2">
            @csrf
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Libur</label>
                <input name="name" required value="{{ old('name') }}" placeholder="Contoh: Hari Kemerdekaan RI" class="w-full rounded-xl border-slate-300">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Jenis</label>
                <select name="type" class="w-full rounded-xl border-slate-300">
                    <option value="national">Libur Nasional</option>
                    <option value="collective_leave">Cuti Bersama</option>
                    <option value="company">Libur Perusahaan</option>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Mulai</label>
                <input type="date" name="start_date" required value="{{ old('start_date') }}" class="w-full rounded-xl border-slate-300">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Selesai</label>
                <input type="date" name="end_date" required value="{{ old('end_date') }}" class="w-full rounded-xl border-slate-300">
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Catatan (opsional)</label>
                <textarea name="description" rows="2" class="w-full rounded-xl border-slate-300" placeholder="Keterangan tambahan...">{{ old('description') }}</textarea>
            </div>
            <div class="lg:col-span-2">
                <button class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-700">+ Tambah Hari Libur</button>
            </div>
        </form>
    </section>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="text-lg font-bold text-slate-900">Daftar Hari Libur</h2>
            <p class="text-sm text-slate-500">Auto Absent tidak akan dibuat pada semua rentang tanggal berikut.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                    <tr><th class="px-6 py-3">Nama</th><th class="px-4 py-3">Jenis</th><th class="px-4 py-3">Tanggal</th><th class="px-4 py-3">Catatan</th><th class="px-6 py-3 text-right">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($holidays as $holiday)
                        @php
                            $typeLabel = match($holiday->type) {
                                'national' => 'Libur Nasional',
                                'collective_leave' => 'Cuti Bersama',
                                default => 'Libur Perusahaan',
                            };
                        @endphp
                        <tr>
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $holiday->name }}</td>
                            <td class="px-4 py-4"><span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $typeLabel }}</span></td>
                            <td class="px-4 py-4 text-slate-600">{{ $holiday->start_date->format('d/m/Y') }} @if(!$holiday->start_date->isSameDay($holiday->end_date)) – {{ $holiday->end_date->format('d/m/Y') }} @endif</td>
                            <td class="px-4 py-4 text-slate-500">{{ $holiday->description ?: '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('attendance.calendar.holidays.edit', $holiday) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Edit</a>
                                    <form method="POST" action="{{ route('attendance.calendar.holidays.destroy', $holiday) }}" onsubmit="return confirm('Hapus hari libur ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-sm font-semibold text-red-600 hover:text-red-700">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada hari libur tambahan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
