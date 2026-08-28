@extends('layouts.app')
@section('title', 'Edit Hari Libur')
@section('content')
<div class="mx-auto max-w-3xl space-y-6 pb-20">
    <div class="flex items-center gap-3"><a href="{{ route('attendance.calendar') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50"><i data-lucide="arrow-left" class="h-4 w-4"></i></a><div><h1 class="text-2xl font-bold text-slate-900">Edit Hari Libur</h1><p class="mt-1 text-sm text-slate-500">Perubahan tanggal akan langsung memengaruhi pengecekan hari kerja dan Auto Absent berikutnya.</p></div></div>
    @if($errors->any())<div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><i data-lucide="circle-alert" class="h-5 w-5"></i>{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('attendance.calendar.holidays.update', $holiday) }}" class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        @csrf @method('PUT')
        <div class="border-b border-slate-100 px-6 py-5"><div class="flex items-center gap-3"><span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600"><i data-lucide="calendar-cog" class="h-5 w-5"></i></span><div><h2 class="font-bold text-slate-900">Detail Hari Libur</h2><p class="text-sm text-slate-500">Pastikan nama, jenis, dan rentang tanggal sudah benar.</p></div></div></div>
        <div class="grid gap-4 p-6 lg:grid-cols-2">
            <div class="lg:col-span-2"><label class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Libur</label><input name="name" required value="{{ old('name', $holiday->name) }}" class="w-full rounded-xl border-slate-300"></div>
            <div><label class="mb-1.5 block text-sm font-semibold text-slate-700">Jenis</label><select name="type" class="w-full rounded-xl border-slate-300"><option value="national" @selected($holiday->type==='national')>Libur Nasional</option><option value="collective_leave" @selected($holiday->type==='collective_leave')>Cuti Bersama</option><option value="company" @selected($holiday->type==='company')>Libur Perusahaan</option></select></div>
            <div class="hidden lg:block"></div>
            <div><label class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Mulai</label><input type="date" name="start_date" required value="{{ old('start_date', $holiday->start_date->toDateString()) }}" class="w-full rounded-xl border-slate-300"></div>
            <div><label class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Selesai</label><input type="date" name="end_date" required value="{{ old('end_date', $holiday->end_date->toDateString()) }}" class="w-full rounded-xl border-slate-300"></div>
            <div class="lg:col-span-2"><label class="mb-1.5 block text-sm font-semibold text-slate-700">Catatan <span class="font-normal text-slate-400">(opsional)</span></label><textarea name="description" rows="3" class="w-full rounded-xl border-slate-300">{{ old('description', $holiday->description) }}</textarea></div>
        </div>
        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4 sm:flex-row sm:justify-end"><a href="{{ route('attendance.calendar') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600">Batal</a><button class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-700"><i data-lucide="save" class="h-4 w-4"></i>Simpan Perubahan</button></div>
    </form>
</div>
@endsection
