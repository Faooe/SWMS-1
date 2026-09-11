@extends('layouts.app')

@section('title', 'Detail Office')
@section('page-title', 'Office')

@section('content')
<div class="space-y-5">
    @if(session('success'))
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            <i data-lucide="circle-check-big" class="mt-0.5 h-4 w-4 shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="flex items-start gap-3 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
            <i data-lucide="circle-alert" class="mt-0.5 h-4 w-4 shrink-0"></i>
            <div><p>Periksa kembali formulir karena masih ada data yang belum valid.</p><p class="mt-1 text-xs font-medium text-rose-600">{{ $errors->first() }}</p></div>
        </div>
    @endif

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-5 px-5 py-5 sm:px-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="flex min-w-0 items-start gap-4">
                <a href="{{ route('offices.index') }}" title="Kembali ke daftar office" class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition hover:bg-slate-200">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <i data-lucide="building-2" class="h-7 w-7"></i>
                </div>
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-[0.1em] text-blue-600">
                        <span>Detail Office</span><span class="text-slate-300">•</span><span class="text-slate-400">{{ $office->code }}</span>
                    </div>
                    <h1 class="mt-1 truncate text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">{{ $office->name }}</h1>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-bold {{ $office->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $office->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $office->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        @if($office->is_head_office)<span class="rounded-full border border-violet-100 bg-violet-50 px-3 py-1.5 text-xs font-bold text-violet-700">Kantor Pusat</span>@endif
                    </div>
                </div>
            </div>
            <p class="max-w-md text-sm leading-6 text-slate-500 lg:text-right">Perbarui identitas, lokasi, dan area attendance office dalam satu halaman.</p>
        </div>

        <div class="grid grid-cols-2 divide-x divide-y divide-slate-100 border-t border-slate-100 sm:grid-cols-4 sm:divide-y-0">
            @foreach([
                ['Employee', $office->employees_count ?? 0, 'Penempatan aktif', 'users', 'bg-blue-50 text-blue-600'],
                ['Assignment', $office->assignments_count ?? 0, 'Total pekerjaan', 'clipboard-list', 'bg-violet-50 text-violet-600'],
                ['Attendance', $office->attendances_count ?? 0, 'Riwayat tercatat', 'calendar-check', 'bg-emerald-50 text-emerald-600'],
                ['Radius', number_format($office->radius).' m', $office->polygon ? 'Dengan polygon' : 'Area melingkar', 'radar', 'bg-amber-50 text-amber-600'],
            ] as [$label, $value, $caption, $icon, $tone])
                <div class="flex items-center gap-3 px-4 py-4 sm:px-5">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $tone }}"><i data-lucide="{{ $icon }}" class="h-5 w-5"></i></div>
                    <div class="min-w-0"><p class="text-lg font-black leading-none text-slate-900">{{ $value }}</p><p class="mt-1 text-xs font-bold text-slate-500">{{ $label }}</p><p class="mt-0.5 truncate text-[11px] text-slate-400">{{ $caption }}</p></div>
                </div>
            @endforeach
        </div>
    </section>

    <form action="{{ route('offices.update', $office) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid items-start gap-5 xl:grid-cols-[minmax(0,1fr)_380px]">
            <div class="min-w-0 space-y-5">
                @include('office.partials.map')
                @include('office.partials.form')
            </div>

            <aside class="space-y-5 xl:sticky xl:top-6">
                @include('office.partials.company-info')
                @include('office.partials.status')
            </aside>
        </div>

        @include('office.partials.action')
    </form>
</div>
@endsection
