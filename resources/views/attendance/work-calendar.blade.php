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
    $holidayCount = is_countable($holidays) ? count($holidays) : $holidays->count();
@endphp

<div class="space-y-6 pb-20">
    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
        <div class="min-w-0">
            <div class="mb-2 flex items-center gap-2 text-xs font-bold uppercase tracking-[0.16em] text-blue-600">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50"><i data-lucide="calendar-range" class="h-4 w-4"></i></span>
                Attendance Policy
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Work Calendar & Hari Libur</h1>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">Kelola hari kerja company dan tanggal pengecualian attendance agar proses Auto Absent mengikuti kalender operasional yang benar.</p>
        </div>
        <a href="{{ route('attendance.index') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke Attendance
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700"><i data-lucide="circle-check" class="h-5 w-5 shrink-0"></i>{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><i data-lucide="circle-alert" class="h-5 w-5 shrink-0"></i>{{ $errors->first() }}</div>
    @endif

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="grid divide-y divide-slate-100 md:grid-cols-3 md:divide-x md:divide-y-0">
            <div class="flex items-center gap-3 px-5 py-4">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $todayIsWorking ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}"><i data-lucide="{{ $todayIsWorking ? 'calendar-check-2' : 'calendar-off' }}" class="h-5 w-5"></i></span>
                <div class="min-w-0"><p class="text-xs font-semibold text-slate-400">Status Hari Ini</p><p class="mt-0.5 truncate text-sm font-bold text-slate-900">{{ $todayIsWorking ? 'Hari Kerja' : 'Non-working Day' }}</p></div>
            </div>
            <div class="flex items-center gap-3 px-5 py-4">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="briefcase-business" class="h-5 w-5"></i></span>
                <div class="min-w-0"><p class="text-xs font-semibold text-slate-400">Hari Kerja Mingguan</p><p class="mt-0.5 text-sm font-bold text-slate-900">{{ $activeDays }} dari 7 hari</p></div>
            </div>
            <div class="flex items-center gap-3 px-5 py-4">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600"><i data-lucide="calendar-off" class="h-5 w-5"></i></span>
                <div class="min-w-0"><p class="text-xs font-semibold text-slate-400">Hari Libur Tercatat</p><p class="mt-0.5 text-sm font-bold text-slate-900">{{ $holidayCount }} periode</p></div>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border {{ $todayIsWorking ? 'border-emerald-200 bg-emerald-50/60' : 'border-amber-200 bg-amber-50/60' }} p-5">
        <div class="flex items-start gap-4">
            <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white {{ $todayIsWorking ? 'text-emerald-600' : 'text-amber-600' }} shadow-sm"><i data-lucide="{{ $todayIsWorking ? 'shield-check' : 'shield-minus' }}" class="h-5 w-5"></i></span>
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2"><h2 class="font-bold text-slate-900">{{ $todayIsWorking ? 'Auto Absent aktif untuk hari ini' : 'Auto Absent dilewati hari ini' }}</h2><span class="rounded-full bg-white px-2.5 py-1 text-[11px] font-bold {{ $todayIsWorking ? 'text-emerald-700' : 'text-amber-700' }} shadow-sm">{{ $todayIsWorking ? 'ACTIVE' : 'SKIPPED' }}</span></div>
                <p class="mt-1 text-sm leading-6 text-slate-600">
                    @if($todayHoliday)
                        Hari ini tercatat sebagai <strong>{{ $todayHoliday }}</strong>. Employee tidak akan dibuat Absent otomatis.
                    @elseif($todayIsWorking)
                        Sistem dapat membuat Auto Absent setelah shift employee berakhir jika tidak ada attendance yang valid.
                    @else
                        Jadwal mingguan company menandai hari ini sebagai hari nonaktif.
                    @endif
                </p>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600"><i data-lucide="calendar-clock" class="h-5 w-5"></i></span>
                <div><h2 class="font-bold text-slate-900">Hari Kerja Mingguan</h2><p class="mt-1 text-sm text-slate-500">Aktifkan hari yang mewajibkan employee melakukan attendance.</p></div>
            </div>
            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700"><span class="h-2 w-2 rounded-full bg-blue-500"></span>{{ $activeDays }} hari aktif</span>
        </div>

        <form method="POST" action="{{ route('attendance.calendar.schedule') }}" class="p-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 xl:grid-cols-7">
                @foreach($dayLabels as $field => [$label, $short])
                    <label class="group relative cursor-pointer">
                        <input type="checkbox" name="{{ $field }}" value="1" @checked($schedule->{$field}) class="peer sr-only">
                        <span class="flex min-h-28 flex-col justify-between rounded-2xl border border-slate-200 bg-slate-50/50 p-4 transition hover:border-slate-300 peer-checked:border-blue-300 peer-checked:bg-blue-50 peer-focus-visible:ring-4 peer-focus-visible:ring-blue-50">
                            <span class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-400 peer-checked:text-blue-600">{{ $short }}</span>
                                <span class="flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-300 transition peer-checked:border-blue-600 peer-checked:bg-blue-600 peer-checked:text-white"><i data-lucide="check" class="h-3.5 w-3.5"></i></span>
                            </span>
                            <div><span class="block font-bold text-slate-800">{{ $label }}</span><span class="mt-0.5 block text-[11px] text-slate-400 peer-checked:text-blue-600">Attendance {{ $schedule->{$field} ? 'aktif' : 'nonaktif' }}</span></div>
                        </span>
                    </label>
                @endforeach
            </div>

            <div class="mt-6 flex flex-col gap-3 rounded-2xl bg-slate-50 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-2 text-xs leading-5 text-slate-500"><i data-lucide="info" class="mt-0.5 h-4 w-4 shrink-0 text-blue-500"></i><span>Perubahan berlaku pada proses Auto Absent berikutnya. Hari nonaktif tidak menghasilkan status Absent otomatis.</span></div>
                <button class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700"><i data-lucide="save" class="h-4 w-4"></i>Simpan Hari Kerja</button>
            </div>
        </form>
    </section>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-violet-50 text-violet-600"><i data-lucide="calendar-off" class="h-5 w-5"></i></span>
                <div><h2 class="font-bold text-slate-900">Hari Libur & Pengecualian</h2><p class="mt-1 text-sm text-slate-500">Libur nasional, cuti bersama, atau hari libur khusus company.</p></div>
            </div>
            <button type="button" aria-controls="holiday-form" aria-expanded="{{ $errors->any() ? 'true' : 'false' }}" onclick="const form=document.getElementById('holiday-form'); const expanded=!form.classList.toggle('hidden'); this.setAttribute('aria-expanded', expanded); this.querySelector('[data-label]').textContent=expanded ? 'Tutup Form' : 'Tambah Hari Libur'; this.querySelector('[data-icon]').setAttribute('data-lucide', expanded ? 'x' : 'plus'); if(window.lucide) window.lucide.createIcons();" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700"><i data-icon data-lucide="{{ $errors->any() ? 'x' : 'plus' }}" class="h-4 w-4"></i><span data-label>{{ $errors->any() ? 'Tutup Form' : 'Tambah Hari Libur' }}</span></button>
        </div>

        <div id="holiday-form" class="{{ $errors->any() ? '' : 'hidden' }} border-b border-slate-100 bg-slate-50/70 p-6">
            <form method="POST" action="{{ route('attendance.calendar.holidays.store') }}" class="grid gap-4 lg:grid-cols-2">
                @csrf
                <div class="lg:col-span-2"><div class="flex items-center gap-3"><span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white text-blue-600 ring-1 ring-slate-200"><i data-lucide="calendar-plus" class="h-4 w-4"></i></span><div><h3 class="font-bold text-slate-900">Tambah hari libur</h3><p class="mt-0.5 text-xs text-slate-500">Rentang tanggal akan otomatis dilewati oleh Auto Absent.</p></div></div></div>
                <div><label class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Libur</label><input name="name" required value="{{ old('name') }}" placeholder="Contoh: Hari Kemerdekaan RI" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50"></div>
                <div><label class="mb-1.5 block text-sm font-semibold text-slate-700">Jenis</label><select name="type" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50"><option value="national">Libur Nasional</option><option value="collective_leave">Cuti Bersama</option><option value="company">Libur Perusahaan</option></select></div>
                <div><label class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Mulai</label><input type="date" name="start_date" required value="{{ old('start_date') }}" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50"></div>
                <div><label class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Selesai</label><input type="date" name="end_date" required value="{{ old('end_date') }}" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50"></div>
                <div class="lg:col-span-2"><label class="mb-1.5 block text-sm font-semibold text-slate-700">Catatan <span class="font-normal text-slate-400">(opsional)</span></label><textarea name="description" rows="2" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50" placeholder="Tambahkan keterangan bila diperlukan...">{{ old('description') }}</textarea></div>
                <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-4 sm:flex-row sm:items-center sm:justify-between lg:col-span-2"><p class="text-xs text-slate-500">Pastikan rentang tanggal sudah benar sebelum disimpan.</p><button class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700"><i data-lucide="plus" class="h-4 w-4"></i>Simpan Hari Libur</button></div>
            </form>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($holidays as $holiday)
                @php
                    [$typeLabel, $typeClass, $typeIconClass] = match($holiday->type) {
                        'national' => ['Libur Nasional', 'bg-red-50 text-red-700', 'bg-red-50 text-red-600'],
                        'collective_leave' => ['Cuti Bersama', 'bg-amber-50 text-amber-700', 'bg-amber-50 text-amber-600'],
                        default => ['Libur Perusahaan', 'bg-violet-50 text-violet-700', 'bg-violet-50 text-violet-600'],
                    };
                @endphp
                <div class="flex flex-col gap-4 px-6 py-5 transition hover:bg-slate-50/60 lg:flex-row lg:items-center">
                    <div class="flex min-w-0 flex-1 items-start gap-3">
                        <div class="mt-0.5 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $typeIconClass }}"><i data-lucide="calendar-x-2" class="h-5 w-5"></i></div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2"><h3 class="font-bold text-slate-900">{{ $holiday->name }}</h3><span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $typeClass }}">{{ $typeLabel }}</span></div>
                            <div class="mt-1.5 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-500"><span class="inline-flex items-center gap-1.5"><i data-lucide="calendar-range" class="h-4 w-4"></i>{{ $holiday->start_date->format('d M Y') }}@if(!$holiday->start_date->isSameDay($holiday->end_date)) – {{ $holiday->end_date->format('d M Y') }}@endif</span>@if($holiday->description)<span class="truncate">{{ $holiday->description }}</span>@endif</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 lg:pl-0">
                        <a href="{{ route('attendance.calendar.holidays.edit', $holiday) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50"><i data-lucide="pencil" class="h-3.5 w-3.5"></i>Edit</a>
                        <form method="POST" action="{{ route('attendance.calendar.holidays.destroy', $holiday) }}" onsubmit="return confirm('Hapus hari libur ini?')">@csrf @method('DELETE')<button class="inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold text-red-600 transition hover:bg-red-50"><i data-lucide="trash-2" class="h-3.5 w-3.5"></i>Hapus</button></form>
                    </div>
                </div>
            @empty
                <div class="px-6 py-14 text-center"><span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400"><i data-lucide="calendar-plus" class="h-6 w-6"></i></span><p class="mt-3 font-bold text-slate-700">Belum ada hari libur tambahan</p><p class="mx-auto mt-1 max-w-md text-sm leading-6 text-slate-400">Tambahkan libur nasional, cuti bersama, atau libur company agar Auto Absent otomatis melewati tanggal tersebut.</p></div>
            @endforelse
        </div>
    </section>
</div>
@endsection
