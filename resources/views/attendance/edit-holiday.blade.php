@extends('layouts.app')
@section('title', 'Edit Hari Libur')
@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <a href="{{ route('attendance.calendar') }}" class="text-sm font-semibold text-blue-600">← Work Calendar</a>
        <h1 class="mt-2 text-2xl font-bold text-slate-900">Edit Hari Libur</h1>
    </div>
    @if($errors->any())<div class="rounded-xl bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('attendance.calendar.holidays.update', $holiday) }}" class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf @method('PUT')
        <div><label class="mb-1.5 block text-sm font-semibold">Nama Libur</label><input name="name" required value="{{ old('name', $holiday->name) }}" class="w-full rounded-xl border-slate-300"></div>
        <div><label class="mb-1.5 block text-sm font-semibold">Jenis</label><select name="type" class="w-full rounded-xl border-slate-300"><option value="national" @selected($holiday->type==='national')>Libur Nasional</option><option value="collective_leave" @selected($holiday->type==='collective_leave')>Cuti Bersama</option><option value="company" @selected($holiday->type==='company')>Libur Perusahaan</option></select></div>
        <div class="grid gap-4 sm:grid-cols-2"><div><label class="mb-1.5 block text-sm font-semibold">Mulai</label><input type="date" name="start_date" required value="{{ old('start_date', $holiday->start_date->toDateString()) }}" class="w-full rounded-xl border-slate-300"></div><div><label class="mb-1.5 block text-sm font-semibold">Selesai</label><input type="date" name="end_date" required value="{{ old('end_date', $holiday->end_date->toDateString()) }}" class="w-full rounded-xl border-slate-300"></div></div>
        <div><label class="mb-1.5 block text-sm font-semibold">Catatan</label><textarea name="description" rows="3" class="w-full rounded-xl border-slate-300">{{ old('description', $holiday->description) }}</textarea></div>
        <button class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white">Simpan Perubahan</button>
    </form>
</div>
@endsection
