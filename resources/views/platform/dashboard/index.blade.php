@extends('layouts.app')

@section('title', 'Platform Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- Header / Greeting --}}
    {{-- ========================================================= --}}
    <section class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500">Dashboard Platform</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">
                Halo, {{ auth()->user()->username ?? 'Platform Administrator' }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Pantau pertumbuhan company, penggunaan platform, dan subscription SWMS dari satu tempat.
            </p>
        </div>

        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 ring-1 ring-inset ring-blue-100">
            <span class="h-2 w-2 rounded-full bg-blue-600"></span>
            Platform Overview
        </span>
    </section>

    {{-- ========================================================= --}}
    {{-- Platform Summary --}}
    {{-- ========================================================= --}}
    <section>
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <i data-lucide="layers-3" class="h-5 w-5"></i>
                </div>
                <div>
                    <p class="font-bold text-slate-900">Ringkasan Platform</p>
                    <p class="text-xs text-slate-500">Kondisi ekosistem SWMS saat ini</p>
                </div>
            </div>

            @php
                $summaryItems = [
                    [
                        'label' => 'Total Company',
                        'value' => $statistics['total'] ?? 0,
                        'icon' => 'building-2',
                        'iconClass' => 'bg-blue-50 text-blue-600',
                    ],
                    [
                        'label' => 'Company Aktif',
                        'value' => $statistics['active'] ?? 0,
                        'icon' => 'badge-check',
                        'iconClass' => 'bg-emerald-50 text-emerald-600',
                    ],
                    [
                        'label' => 'Company Premium',
                        'value' => $statistics['premium'] ?? 0,
                        'icon' => 'gem',
                        'iconClass' => 'bg-violet-50 text-violet-600',
                    ],
                    [
                        'label' => 'Total Employee',
                        'value' => $statistics['employees'] ?? 0,
                        'icon' => 'users',
                        'iconClass' => 'bg-blue-50 text-blue-600',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-2 gap-px bg-slate-200 lg:grid-cols-4">
                @foreach($summaryItems as $item)
                    <div class="bg-white p-4 sm:p-5">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $item['iconClass'] }}">
                                <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-2xl font-bold leading-none text-slate-950">{{ $item['value'] }}</p>
                                <p class="mt-1.5 truncate text-xs font-medium text-slate-500">{{ $item['label'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ========================================================= --}}
    {{-- Platform Status + Quick Access --}}
    {{-- ========================================================= --}}
    <section class="grid gap-5 xl:grid-cols-[1.15fr_1.85fr]">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="font-bold text-slate-900">Status Ekosistem</h2>
                    <p class="text-xs text-slate-500">Distribusi dan kondisi company</p>
                </div>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i data-lucide="activity" class="h-5 w-5"></i>
                </span>
            </div>

            <div class="divide-y divide-slate-100 px-5">
                <div class="flex items-center gap-3 py-4">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                        <i data-lucide="package" class="h-4 w-4"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-slate-900">Free Plan</p>
                        <p class="text-xs text-slate-500">Company pada paket dasar</p>
                    </div>
                    <span class="text-lg font-bold text-slate-900">{{ $statistics['free'] ?? 0 }}</span>
                </div>

                <div class="flex items-center gap-3 py-4">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <i data-lucide="circle-pause" class="h-4 w-4"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-slate-900">Company Nonaktif</p>
                        <p class="text-xs text-slate-500">Company yang sedang dinonaktifkan</p>
                    </div>
                    <span class="text-lg font-bold text-slate-900">{{ $statistics['inactive'] ?? 0 }}</span>
                </div>

                <div class="flex items-center gap-3 py-4">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                        <i data-lucide="calendar-x-2" class="h-4 w-4"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-slate-900">Premium Expired</p>
                        <p class="text-xs text-slate-500">Subscription premium yang sudah berakhir</p>
                    </div>
                    <span class="text-lg font-bold text-slate-900">{{ $statistics['expired'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-bold text-slate-900">Akses Cepat</h2>
                <p class="text-xs text-slate-500">Masuk ke pengelolaan platform yang paling sering digunakan</p>
            </div>

            <div class="grid sm:grid-cols-3">
                <a href="{{ route('platform.companies.index') }}"
                   class="group flex min-h-32 items-start gap-3 border-b border-slate-100 p-5 transition hover:bg-slate-50 sm:border-b-0 sm:border-r">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="building-2" class="h-5 w-5"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-bold text-slate-900">Companies</p>
                            <i data-lucide="chevron-right" class="h-4 w-4 shrink-0 text-slate-300 transition group-hover:text-blue-600"></i>
                        </div>
                        <p class="mt-1 text-xs leading-5 text-slate-500">Kelola company dan status akses platform.</p>
                    </div>
                </a>

                <a href="{{ route('platform.premium.index') }}"
                   class="group flex min-h-32 items-start gap-3 border-b border-slate-100 p-5 transition hover:bg-slate-50 sm:border-b-0 sm:border-r">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                        <i data-lucide="gem" class="h-5 w-5"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-bold text-slate-900">Premium</p>
                            <i data-lucide="chevron-right" class="h-4 w-4 shrink-0 text-slate-300 transition group-hover:text-blue-600"></i>
                        </div>
                        <p class="mt-1 text-xs leading-5 text-slate-500">Pantau dan kelola subscription company.</p>
                    </div>
                </a>

                <a href="{{ route('platform.profile.edit') }}"
                   class="group flex min-h-32 items-start gap-3 p-5 transition hover:bg-slate-50">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                        <i data-lucide="user-circle" class="h-5 w-5"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-bold text-slate-900">Profile</p>
                            <i data-lucide="chevron-right" class="h-4 w-4 shrink-0 text-slate-300 transition group-hover:text-blue-600"></i>
                        </div>
                        <p class="mt-1 text-xs leading-5 text-slate-500">Kelola identitas Platform Administrator.</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- ========================================================= --}}
    {{-- Latest Companies --}}
    {{-- ========================================================= --}}
    <section>
        <div class="mb-3">
            <h2 class="text-lg font-bold text-slate-950">Company Terbaru</h2>
            <p class="text-sm text-slate-500">Company yang paling baru terdaftar pada platform SWMS.</p>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="building" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-slate-900">Pendaftaran Terbaru</h3>
                        <p class="text-xs text-slate-500">5 company terbaru</p>
                    </div>
                </div>

                <a href="{{ route('platform.companies.index') }}"
                   class="inline-flex w-fit items-center gap-1.5 text-sm font-bold text-blue-600 transition hover:text-blue-700">
                    Lihat Semua
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
            </div>

            {{-- Desktop table --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="min-w-full">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Company</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Plan</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Kapasitas</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Terdaftar</th>
                            <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($latestCompanies as $company)
                            @php
                                $planClass = match($company->subscription_plan) {
                                    'Premium Go' => 'bg-blue-50 text-blue-700 ring-blue-100',
                                    'Premium Plus' => 'bg-violet-50 text-violet-700 ring-violet-100',
                                    'Premium Max' => 'bg-rose-50 text-rose-700 ring-rose-100',
                                    default => 'bg-slate-100 text-slate-600 ring-slate-200',
                                };
                                $planIcon = match($company->subscription_plan) {
                                    'Premium Go' => 'zap',
                                    'Premium Plus' => 'crown',
                                    'Premium Max' => 'sparkles',
                                    default => 'package',
                                };
                            @endphp
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($company->logo)
                                            <img src="{{ secure_file_url($company->logo) }}"
                                                 alt="{{ $company->name }}"
                                                 class="h-10 w-10 shrink-0 rounded-xl object-cover ring-1 ring-slate-200">
                                        @else
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold text-blue-700 ring-1 ring-inset ring-blue-100">
                                                {{ strtoupper(substr($company->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="max-w-56 truncate text-sm font-bold text-slate-900">{{ $company->name }}</p>
                                            <p class="mt-0.5 text-xs text-slate-500">{{ $company->code }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-bold ring-1 ring-inset {{ $planClass }}">
                                        <i data-lucide="{{ $planIcon }}" class="h-3.5 w-3.5"></i>
                                        {{ $company->subscription_plan ?? 'Free' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-900">{{ number_format((int) $company->max_employee) }}</p>
                                    <p class="text-xs text-slate-500">employee</p>
                                </td>
                                <td class="px-5 py-4">
                                    @if($company->is_active)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-100">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600 ring-1 ring-inset ring-slate-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-sm font-medium text-slate-700">{{ optional($company->created_at)->translatedFormat('d M Y') ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('platform.companies.show', $company) }}"
                                       class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                        Detail
                                        <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                        <i data-lucide="building-2" class="h-5 w-5"></i>
                                    </span>
                                    <p class="mt-3 text-sm font-semibold text-slate-700">Belum ada company</p>
                                    <p class="mt-1 text-xs text-slate-400">Company yang baru terdaftar akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="divide-y divide-slate-100 md:hidden">
                @forelse($latestCompanies as $company)
                    @php
                        $mobilePlanClass = match($company->subscription_plan) {
                            'Premium Go' => 'bg-blue-50 text-blue-700 ring-blue-100',
                            'Premium Plus' => 'bg-violet-50 text-violet-700 ring-violet-100',
                            'Premium Max' => 'bg-rose-50 text-rose-700 ring-rose-100',
                            default => 'bg-slate-100 text-slate-600 ring-slate-200',
                        };
                    @endphp
                    <a href="{{ route('platform.companies.show', $company) }}" class="block p-5 transition hover:bg-slate-50">
                        <div class="flex items-start gap-3">
                            @if($company->logo)
                                <img src="{{ secure_file_url($company->logo) }}"
                                     alt="{{ $company->name }}"
                                     class="h-11 w-11 shrink-0 rounded-xl object-cover ring-1 ring-slate-200">
                            @else
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold text-blue-700 ring-1 ring-inset ring-blue-100">
                                    {{ strtoupper(substr($company->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-900">{{ $company->name }}</p>
                                        <p class="mt-0.5 text-xs text-slate-500">{{ $company->code }}</p>
                                    </div>
                                    <i data-lucide="chevron-right" class="mt-1 h-4 w-4 shrink-0 text-slate-300"></i>
                                </div>

                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold ring-1 ring-inset {{ $mobilePlanClass }}">
                                        {{ $company->subscription_plan ?? 'Free' }}
                                    </span>
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold ring-1 ring-inset {{ $company->is_active ? 'bg-emerald-50 text-emerald-700 ring-emerald-100' : 'bg-slate-100 text-slate-600 ring-slate-200' }}">
                                        {{ $company->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-12 text-center">
                        <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <i data-lucide="building-2" class="h-5 w-5"></i>
                        </span>
                        <p class="mt-3 text-sm font-semibold text-slate-700">Belum ada company</p>
                        <p class="mt-1 text-xs text-slate-400">Company yang baru terdaftar akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

</div>
@endsection
