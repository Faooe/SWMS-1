@extends('layouts.app')

@section('title', 'Edit Hari Libur')

@section('content')
<div class="space-y-6 pb-20">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-purple-600"><span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-purple-50"><i data-lucide="calendar-cog" class="h-4 w-4"></i></span>Attendance Configuration</div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Hari Libur</h1>
            <p class="mt-1 max-w-2xl text-sm text-slate-500">Perbarui tanggal pengecualian agar jadwal kerja dan Auto Absent tetap akurat.</p>
        </div>
        <a href="{{ route('attendance.calendar') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"><i data-lucide="arrow-left" class="h-4 w-4"></i>Kembali ke Work Calendar</a>
    </div>

    @if($errors->any())
        <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><i data-lucide="circle-alert" class="h-5 w-5 shrink-0"></i>{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('attendance.calendar.holidays.update', $holiday) }}" class="mx-auto max-w-4xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        @csrf @method('PUT')
        <div class="border-b border-slate-100 bg-gradient-to-r from-purple-50/80 to-white px-6 py-6"><div class="flex items-start gap-3"><span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white text-purple-600 shadow-sm ring-1 ring-purple-100"><i data-lucide="calendar-range" class="h-5 w-5"></i></span><div><div class="flex flex-wrap items-center gap-2"><h2 class="font-bold text-slate-900">Detail Hari Libur</h2><span class="rounded-full bg-purple-100 px-2.5 py-1 text-[11px] font-bold text-purple-700">{{ $holiday->type === 'national' ? 'Libur Nasional' : ($holiday->type === 'collective_leave' ? 'Cuti Bersama' : 'Libur Perusahaan') }}</span></div><p class="mt-1 text-sm text-slate-500">Pastikan nama, jenis, dan rentang tanggal sudah benar.</p></div></div></div>
        <div class="grid gap-5 p-6 lg:grid-cols-2">
            <div class="lg:col-span-2"><label for="holiday-name" class="mb-2 block text-sm font-semibold text-slate-700">Nama libur</label><input id="holiday-name" name="name" required value="{{ old('name', $holiday->name) }}" placeholder="Contoh: Hari Kemerdekaan RI" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div><label for="holiday-type" class="mb-2 block text-sm font-semibold text-slate-700">Jenis</label><select id="holiday-type" name="type" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"><option value="national" @selected($holiday->type==='national')>Libur Nasional</option><option value="collective_leave" @selected($holiday->type==='collective_leave')>Cuti Bersama</option><option value="company" @selected($holiday->type==='company')>Libur Perusahaan</option></select></div>
            <div><label for="holiday-start" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal mulai</label><input id="holiday-start" type="date" name="start_date" required value="{{ old('start_date', $holiday->start_date->toDateString()) }}" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div><label for="holiday-end" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal selesai</label><input id="holiday-end" type="date" name="end_date" required value="{{ old('end_date', $holiday->end_date->toDateString()) }}" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div class="lg:col-span-2"><label for="holiday-description" class="mb-2 block text-sm font-semibold text-slate-700">Catatan <span class="font-normal text-slate-400">(opsional)</span></label><textarea id="holiday-description" name="description" rows="3" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Tambahkan keterangan bila diperlukan...">{{ old('description', $holiday->description) }}</textarea></div>
        </div>
        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4 sm:flex-row sm:items-center sm:justify-between"><p class="text-xs text-slate-500">Tanggal pada rentang ini akan dilewati oleh Auto Absent.</p><div class="flex flex-col-reverse gap-3 sm:flex-row"><a href="{{ route('attendance.calendar') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Batal</a><button class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700"><i data-lucide="save" class="h-4 w-4"></i>Simpan Perubahan</button></div></div>
    </form>
</div>
@endsection
