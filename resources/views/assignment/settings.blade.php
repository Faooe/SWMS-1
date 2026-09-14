@extends('layouts.app')

@section('title', 'Pengaturan Assignment')
@section('page-title', 'Pengaturan Assignment')

@section('content')
<div class="space-y-6 pb-20">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-blue-600">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50"><i data-lucide="sliders-horizontal" class="h-4 w-4"></i></span>
                Assignment Configuration
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pengaturan Assignment</h1>
            <p class="mt-1 max-w-2xl text-sm text-slate-500">Atur alur review dan batas waktu revisi agar proses kerja employee lebih konsisten.</p>
        </div>
        <a href="{{ route('assignments.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke Assignment
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700"><i data-lucide="circle-check" class="h-5 w-5 shrink-0"></i>{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><i data-lucide="circle-alert" class="h-5 w-5 shrink-0"></i>{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('assignment-settings.update') }}" class="mx-auto max-w-4xl space-y-5">
        @csrf @method('PUT')

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5"><div class="flex items-start gap-3"><span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600"><i data-lucide="badge-check" class="h-5 w-5"></i></span><div class="min-w-0"><h2 class="font-bold text-slate-900">Alur persetujuan</h2><p class="mt-1 text-sm text-slate-500">Tentukan apakah hasil kerja langsung disetujui atau menunggu pemeriksaan manual.</p></div></div></div>
            <div class="p-6">
                <label class="flex cursor-pointer items-center justify-between gap-5 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-blue-200 hover:bg-blue-50/40">
                    <span class="flex min-w-0 items-start gap-3"><span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $company->assignment_auto_approve ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-500' }}"><i data-lucide="zap" class="h-4 w-4"></i></span><span class="min-w-0"><span class="flex flex-wrap items-center gap-2 font-bold text-slate-800">Auto Approve <span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $company->assignment_auto_approve ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">{{ $company->assignment_auto_approve ? 'Aktif' : 'Nonaktif' }}</span></span><span class="mt-1 block text-sm leading-6 text-slate-500">Hasil kerja employee otomatis disetujui tanpa perlu direview manual.</span></span></span>
                    <span class="relative inline-flex shrink-0 items-center"><input type="checkbox" name="assignment_auto_approve" value="1" {{ $company->assignment_auto_approve ? 'checked' : '' }} class="peer sr-only"><span class="h-7 w-12 rounded-full bg-slate-200 transition peer-checked:bg-blue-600 peer-focus-visible:ring-4 peer-focus-visible:ring-blue-100 after:absolute after:left-1 after:top-1 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow-sm after:transition-all after:content-[''] peer-checked:after:translate-x-5"></span></span>
                </label>
                <div class="mt-4 flex items-start gap-2 text-xs leading-5 text-slate-500"><i data-lucide="info" class="mt-0.5 h-4 w-4 shrink-0 text-blue-500"></i><span>Nonaktifkan bila setiap submission perlu diverifikasi oleh reviewer sebelum dinyatakan selesai.</span></div>
            </div>
        </section>

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5"><div class="flex items-start gap-3"><span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-purple-50 text-purple-600"><i data-lucide="timer-reset" class="h-5 w-5"></i></span><div class="min-w-0"><h2 class="font-bold text-slate-900">Durasi revisi default</h2><p class="mt-1 text-sm text-slate-500">Batas waktu employee untuk mengirim ulang hasil kerja setelah ditolak. Berlaku jika reviewer tidak menentukan durasi khusus.</p></div></div></div>
            <div class="p-6"><label for="assignment_revision_minutes" class="mb-2 block text-sm font-semibold text-slate-700">Durasi (menit)</label><div class="relative max-w-sm"><input id="assignment_revision_minutes" type="number" name="assignment_revision_minutes" value="{{ old('assignment_revision_minutes', $company->assignment_revision_minutes) }}" min="5" max="43200" required class="w-full rounded-xl border-slate-300 px-4 py-3 pr-20 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"><span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-sm font-semibold text-slate-400">menit</span></div><p class="mt-2 text-xs text-slate-500">Masukkan nilai antara 5 menit sampai 30 hari (43.200 menit).</p>@error('assignment_revision_minutes')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                <div class="mt-4 flex flex-wrap items-center gap-2"><span class="mr-1 text-xs font-semibold text-slate-400">Pilih cepat:</span>@foreach([['1 Jam', 60], ['6 Jam', 360], ['1 Hari', 1440], ['3 Hari', 4320]] as [$label, $minutes])<button type="button" onclick="document.getElementById('assignment_revision_minutes').value = {{ $minutes }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700">{{ $label }}</button>@endforeach</div>
            </div>
        </section>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"><a href="{{ route('assignments.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Batal</a><button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700"><i data-lucide="save" class="h-4 w-4"></i>Simpan Pengaturan</button></div>
    </form>
</div>
@endsection
