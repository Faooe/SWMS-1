@extends('layouts.app')

@section('title', 'Work Calendar')

@section('content')
@php
    $dayLabels = [
        'monday' => ['Senin', 'Sen'],
        'tuesday' => ['Selasa', 'Sel'],
        'wednesday' => ['Rabu', 'Rab'],
        'thursday' => ['Kamis', 'Kam'],
        'friday' => ['Jumat', 'Jum'],
        'saturday' => ['Sabtu', 'Sab'],
        'sunday' => ['Minggu', 'Min'],
    ];
    $activeDays = collect(array_keys($dayLabels))->filter(fn ($day) => (bool) $schedule->{$day})->count();
    $todayIsWorking = (bool) ($todayInfo['is_working_day'] ?? false);
    $todayHoliday = $todayInfo['holiday']['name'] ?? null;
@endphp

<div class="space-y-6 pb-20">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-blue-600">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50"><i data-lucide="calendar-days" class="h-4 w-4"></i></span>
                Attendance Configuration
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Work Calendar & Hari Libur</h1>
            <p class="mt-1 max-w-2xl text-sm text-slate-500">Tentukan kapan attendance diwajibkan. Auto Absent hanya berjalan pada hari kerja aktif dan otomatis melewati tanggal libur.</p>
        </div>
        <a href="{{ route('attendance.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke Attendance
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            <i data-lucide="circle-check" class="h-5 w-5 shrink-0"></i>{{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i data-lucide="circle-alert" class="h-5 w-5 shrink-0"></i>{{ $errors->first() }}
        </div>
    @endif

    <section class="grid gap-4 lg:grid-cols-[1.35fr_.65fr]">
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $todayIsWorking ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                    <i data-lucide="{{ $todayIsWorking ? 'calendar-check-2' : 'calendar-off' }}" class="h-6 w-6"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="font-bold text-slate-900">{{ $todayIsWorking ? 'Hari ini adalah hari kerja' : 'Hari ini bukan hari kerja' }}</h2>
                        <span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $todayIsWorking ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">{{ $todayIsWorking ? 'AUTO ABSENT AKTIF' : 'AUTO ABSENT DILEWATI' }}</span>
                    </div>
                    <p class="mt-1 text-sm text-slate-500">
                        @if($todayHoliday)
                            {{ $todayHoliday }}. Employee tidak akan dibuat Absent karena tanggal ini terdaftar sebagai hari libur.
                        @elseif($todayIsWorking)
                            Auto Absent dapat berjalan setelah shift employee berakhir jika tidak ada attendance yang valid.
                        @else
                            Jadwal mingguan menandai hari ini sebagai non-working day.
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Jadwal aktif</p>
            <div class="mt-2 flex items-end gap-2"><span class="text-3xl font-bold text-slate-900">{{ $activeDays }}</span><span class="pb-1 text-sm text-slate-500">hari / minggu</span></div>
            <p class="mt-1 text-xs text-slate-500">Bisa disesuaikan dengan kebijakan company.</p>
        </div>
    </section>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="briefcase-business" class="h-5 w-5"></i></span>
                <div>
                    <h2 class="font-bold text-slate-900">Hari Kerja Mingguan</h2>
                    <p class="text-sm text-slate-500">Pilih hari yang mewajibkan attendance. Hari nonaktif tidak menghasilkan Auto Absent.</p>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('attendance.calendar.schedule') }}" class="p-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 xl:grid-cols-7">
                @foreach($dayLabels as $field => [$label, $short])
                    <label class="group relative cursor-pointer">
                        <input type="checkbox" name="{{ $field }}" value="1" @checked($schedule->{$field}) class="peer sr-only">
                        <span class="flex min-h-24 flex-col justify-between rounded-2xl border border-slate-200 bg-white p-4 transition peer-checked:border-blue-300 peer-checked:bg-blue-50 group-hover:border-slate-300">
                            <span class="flex items-center justify-between gap-2">
                                <span class="text-xs font-bold uppercase tracking-wide text-slate-400 peer-checked:text-blue-600">{{ $short }}</span>
                                <span class="flex h-6 w-6 items-center justify-center rounded-full border border-slate-200 text-transparent peer-checked:border-blue-600 peer-checked:bg-blue-600 peer-checked:text-white"><i data-lucide="check" class="h-3.5 w-3.5"></i></span>
                            </span>
                            <span class="mt-4 font-bold text-slate-800">{{ $label }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
            <div class="mt-5 flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-slate-500"><strong class="text-slate-700">Catatan:</strong> perubahan berlaku untuk proses Auto Absent berikutnya.</p>
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700"><i data-lucide="save" class="h-4 w-4"></i>Simpan Hari Kerja</button>
            </div>
        </form>
    </section>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600"><i data-lucide="calendar-off" class="h-5 w-5"></i></span>
                <div>
                    <h2 class="font-bold text-slate-900">Hari Libur</h2>
                    <p class="text-sm text-slate-500">Kelola libur nasional, cuti bersama, dan libur khusus company.</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('holiday-form').classList.toggle('hidden')" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-700"><i data-lucide="plus" class="h-4 w-4"></i>Tambah Hari Libur</button>
        </div>

        <div id="holiday-form" class="{{ $errors->any() ? '' : 'hidden' }} border-b border-slate-100 bg-slate-50/60 p-6">
            <form method="POST" action="{{ route('attendance.calendar.holidays.store') }}" class="grid gap-4 lg:grid-cols-2">
                @csrf
                <div class="lg:col-span-2"><h3 class="font-bold text-slate-900">Hari libur baru</h3><p class="mt-1 text-xs text-slate-500">Tanggal dalam rentang ini akan dilewati oleh Auto Absent.</p></div>
                <div><label class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Libur</label><input name="name" required value="{{ old('name') }}" placeholder="Contoh: Hari Kemerdekaan RI" class="w-full rounded-xl border-slate-300 bg-white"></div>
                <div><label class="mb-1.5 block text-sm font-semibold text-slate-700">Jenis</label><select name="type" class="w-full rounded-xl border-slate-300 bg-white"><option value="national">Libur Nasional</option><option value="collective_leave">Cuti Bersama</option><option value="company">Libur Perusahaan</option></select></div>
                <div><label class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Mulai</label><input type="date" name="start_date" required value="{{ old('start_date') }}" class="w-full rounded-xl border-slate-300 bg-white"></div>
                <div><label class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Selesai</label><input type="date" name="end_date" required value="{{ old('end_date') }}" class="w-full rounded-xl border-slate-300 bg-white"></div>
                <div class="lg:col-span-2"><label class="mb-1.5 block text-sm font-semibold text-slate-700">Catatan <span class="font-normal text-slate-400">(opsional)</span></label><textarea name="description" rows="2" class="w-full rounded-xl border-slate-300 bg-white" placeholder="Tambahkan keterangan bila diperlukan...">{{ old('description') }}</textarea></div>
                <div class="lg:col-span-2 flex justify-end"><button class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-700"><i data-lucide="plus" class="h-4 w-4"></i>Simpan Hari Libur</button></div>
            </form>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($holidays as $holiday)
                @php
                    [$typeLabel, $typeClass] = match($holiday->type) {
                        'national' => ['Libur Nasional', 'bg-red-50 text-red-700'],
                        'collective_leave' => ['Cuti Bersama', 'bg-amber-50 text-amber-700'],
                        default => ['Libur Perusahaan', 'bg-purple-50 text-purple-700'],
                    };
                @endphp
                <div class="flex flex-col gap-4 px-6 py-5 transition hover:bg-slate-50/70 lg:flex-row lg:items-center">
                    <div class="flex min-w-0 flex-1 items-start gap-3">
                        <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500"><i data-lucide="calendar-x-2" class="h-5 w-5"></i></div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2"><h3 class="font-bold text-slate-900">{{ $holiday->name }}</h3><span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $typeClass }}">{{ $typeLabel }}</span></div>
                            <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-500"><span class="inline-flex items-center gap-1.5"><i data-lucide="calendar-range" class="h-4 w-4"></i>{{ $holiday->start_date->format('d M Y') }}@if(!$holiday->start_date->isSameDay($holiday->end_date)) – {{ $holiday->end_date->format('d M Y') }}@endif</span>@if($holiday->description)<span>{{ $holiday->description }}</span>@endif</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 pl-13 lg:pl-0">
                        <a href="{{ route('attendance.calendar.holidays.edit', $holiday) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50"><i data-lucide="pencil" class="h-3.5 w-3.5"></i>Edit</a>
                        <form method="POST" action="{{ route('attendance.calendar.holidays.destroy', $holiday) }}" onsubmit="return confirm('Hapus hari libur ini?')">@csrf @method('DELETE')<button class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50"><i data-lucide="trash-2" class="h-3.5 w-3.5"></i>Hapus</button></form>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center"><span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400"><i data-lucide="calendar-plus" class="h-6 w-6"></i></span><p class="mt-3 font-semibold text-slate-600">Belum ada hari libur tambahan</p><p class="mt-1 text-sm text-slate-400">Tambahkan libur agar Auto Absent otomatis melewati tanggal tersebut.</p></div>
            @endforelse
        </div>
    </section>
</div>
@endsection
