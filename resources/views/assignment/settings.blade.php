@extends('layouts.app')

@section('title', 'Pengaturan Assignment')
@section('page-title', 'Pengaturan Assignment')

@section('content')
@php
    $autoApprove = (bool) $company->assignment_auto_approve;
    $revisionMinutes = (int) old('assignment_revision_minutes', $company->assignment_revision_minutes);
    $revisionLabel = $revisionMinutes >= 1440
        ? rtrim(rtrim(number_format($revisionMinutes / 1440, 1), '0'), '.') . ' hari'
        : ($revisionMinutes >= 60
            ? rtrim(rtrim(number_format($revisionMinutes / 60, 1), '0'), '.') . ' jam'
            : $revisionMinutes . ' menit');
@endphp

<div class="space-y-6 pb-20">
    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
        <div class="min-w-0">
            <div class="mb-2 flex items-center gap-2 text-xs font-bold uppercase tracking-[0.16em] text-blue-600">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50"><i data-lucide="settings-2" class="h-4 w-4"></i></span>
                Assignment Policy
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Auto Approve & Review Assignment</h1>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">Atur bagaimana hasil kerja employee diproses setelah selesai dan berapa lama waktu revisi diberikan ketika pekerjaan perlu diperbaiki.</p>
        </div>
        <a href="{{ route('assignments.index') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke Assignment
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
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $autoApprove ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}"><i data-lucide="{{ $autoApprove ? 'badge-check' : 'user-check' }}" class="h-5 w-5"></i></span>
                <div class="min-w-0"><p class="text-xs font-semibold text-slate-400">Approval Mode</p><p class="mt-0.5 truncate text-sm font-bold text-slate-900">{{ $autoApprove ? 'Auto Approve' : 'Review Manual' }}</p></div>
            </div>
            <div class="flex items-center gap-3 px-5 py-4">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="timer-reset" class="h-5 w-5"></i></span>
                <div class="min-w-0"><p class="text-xs font-semibold text-slate-400">Batas Revisi Default</p><p class="mt-0.5 truncate text-sm font-bold text-slate-900">{{ $revisionLabel }}</p></div>
            </div>
            <div class="flex items-center gap-3 px-5 py-4">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600"><i data-lucide="workflow" class="h-5 w-5"></i></span>
                <div class="min-w-0"><p class="text-xs font-semibold text-slate-400">Workflow</p><p class="mt-0.5 truncate text-sm font-bold text-slate-900">Company Assignment</p></div>
            </div>
        </div>
    </section>

    <form method="POST" action="{{ route('assignment-settings.update') }}" class="space-y-5">
        @csrf @method('PUT')

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-3">
                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600"><i data-lucide="badge-check" class="h-5 w-5"></i></span>
                    <div>
                        <h2 class="font-bold text-slate-900">Approval hasil kerja</h2>
                        <p class="mt-1 text-sm text-slate-500">Pilih apakah submission employee langsung selesai atau tetap masuk antrean review.</p>
                    </div>
                </div>
                <span class="inline-flex w-fit items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold {{ $autoApprove ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                    <span class="h-2 w-2 rounded-full {{ $autoApprove ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $autoApprove ? 'Auto Approve Aktif' : 'Review Manual Aktif' }}
                </span>
            </div>

            <div class="grid gap-4 p-6 lg:grid-cols-[1.1fr_.9fr]">
                <label class="relative cursor-pointer">
                    <input type="checkbox" name="assignment_auto_approve" value="1" {{ $autoApprove ? 'checked' : '' }} class="peer sr-only">
                    <div class="flex min-h-32 items-start gap-4 rounded-2xl border border-slate-200 bg-slate-50/60 p-5 pr-20 transition hover:border-blue-200 hover:bg-blue-50/40 peer-checked:border-blue-300 peer-checked:bg-blue-50">
                        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white text-blue-600 ring-1 ring-slate-200"><i data-lucide="zap" class="h-5 w-5"></i></span>
                        <div class="min-w-0 flex-1"><p class="font-bold text-slate-900">Auto Approve</p><p class="mt-1 text-sm leading-6 text-slate-500">Submission yang selesai langsung menjadi approved tanpa menunggu tindakan reviewer.</p></div>
                    </div>
                    <span class="absolute right-5 top-5 h-7 w-12 rounded-full bg-slate-300 transition peer-checked:bg-blue-600 after:absolute after:left-1 after:top-1 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow-sm after:transition-all after:content-[''] peer-checked:after:translate-x-5"></span>
                </label>

                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <div class="flex items-start gap-3">
                        <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i data-lucide="shield-check" class="h-4 w-4"></i></span>
                        <div><p class="text-sm font-bold text-slate-900">Kapan sebaiknya digunakan?</p><p class="mt-1 text-xs leading-5 text-slate-500">Aktifkan jika proses kerja tidak membutuhkan quality review manual. Untuk pekerjaan kritikal, biarkan nonaktif agar reviewer tetap memeriksa hasil employee.</p></div>
                    </div>
                    <div class="mt-4 rounded-xl bg-slate-50 px-3.5 py-3 text-xs leading-5 text-slate-500"><strong class="text-slate-700">Catatan:</strong> perubahan hanya memengaruhi proses approval berikutnya dan tidak mengubah assignment yang sudah selesai.</div>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <div class="flex items-start gap-3">
                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-violet-50 text-violet-600"><i data-lucide="timer-reset" class="h-5 w-5"></i></span>
                    <div><h2 class="font-bold text-slate-900">Batas waktu revisi</h2><p class="mt-1 text-sm text-slate-500">Durasi default saat reviewer meminta employee memperbaiki hasil kerja.</p></div>
                </div>
            </div>
            <div class="grid gap-6 p-6 lg:grid-cols-[.8fr_1.2fr]">
                <div>
                    <label for="assignment_revision_minutes" class="mb-2 block text-sm font-semibold text-slate-700">Durasi revisi default</label>
                    <div class="relative">
                        <input id="assignment_revision_minutes" type="number" name="assignment_revision_minutes" value="{{ old('assignment_revision_minutes', $company->assignment_revision_minutes) }}" min="5" max="43200" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 pr-20 text-sm font-semibold text-slate-900 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50">
                        <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-xs font-semibold text-slate-400">menit</span>
                    </div>
                    @error('assignment_revision_minutes')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-2 text-xs leading-5 text-slate-500">Minimum 5 menit, maksimum 30 hari (43.200 menit).</p>
                </div>

                <div>
                    <p class="mb-2 text-sm font-semibold text-slate-700">Preset cepat</p>
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                        @foreach([['1 Jam', 60], ['6 Jam', 360], ['1 Hari', 1440], ['3 Hari', 4320]] as [$label, $minutes])
                            <button type="button" onclick="document.getElementById('assignment_revision_minutes').value = {{ $minutes }}" class="group rounded-xl border border-slate-200 bg-white px-3 py-3 text-left transition hover:border-blue-300 hover:bg-blue-50">
                                <span class="block text-sm font-bold text-slate-700 group-hover:text-blue-700">{{ $label }}</span>
                                <span class="mt-0.5 block text-[11px] text-slate-400">{{ number_format($minutes) }} menit</span>
                            </button>
                        @endforeach
                    </div>
                    <div class="mt-4 flex items-start gap-2 rounded-xl bg-blue-50 px-3.5 py-3 text-xs leading-5 text-blue-700"><i data-lucide="info" class="mt-0.5 h-4 w-4 shrink-0"></i><span>Durasi khusus yang diatur reviewer pada saat meminta revisi tetap memiliki prioritas dibanding nilai default ini.</span></div>
                </div>
            </div>
        </section>

        <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs leading-5 text-slate-500">Pastikan policy sesuai proses review company sebelum menyimpan perubahan.</p>
            <div class="flex gap-2">
                <a href="{{ route('assignments.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700"><i data-lucide="save" class="h-4 w-4"></i>Simpan Pengaturan</button>
            </div>
        </div>
    </form>
</div>
@endsection
