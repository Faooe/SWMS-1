@extends('layouts.app')

@section('title', 'Platform Dashboard')

@section('content')

<div class="space-y-8">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Platform Dashboard
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Monitor seluruh perusahaan yang menggunakan Smart Workforce Management System.
        </p>
    </div>

    {{-- ========================================================= --}}
    {{-- Statistic --}}
    {{-- ========================================================= --}}
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Total Company</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">
                    <i data-lucide="building-2" class="h-5 w-5 text-blue-600"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">
                {{ $statistics['total'] }}
            </h2>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Active Company</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100">
                    <i data-lucide="circle-check" class="h-5 w-5 text-green-600"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">
                {{ $statistics['active'] }}
            </h2>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Inactive Company</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-100">
                    <i data-lucide="circle-x" class="h-5 w-5 text-red-600"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">
                {{ $statistics['inactive'] }}
            </h2>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Premium Company</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100">
                    <i data-lucide="gem" class="h-5 w-5 text-amber-600"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">
                {{ $statistics['premium'] }}
            </h2>
        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- Latest Company --}}
    {{-- ========================================================= --}}
    <div class="rounded-2xl bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Latest Companies
            </h2>
            {{-- Bagian tag pembuka <a> yang tadinya rusak/terpotong sudah diperbaiki di bawah ini --}}
            <a href="{{ route('platform.companies.index') }}" class="text-sm font-semibold text-blue-600 transition hover:text-blue-700">
                Lihat Semua
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Company
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Plan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Employee Limit
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestCompanies as $company)
                        <tr class="border-t border-slate-100">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-sm font-bold text-blue-700">
                                        {{ strtoupper(substr($company->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800">
                                            {{ $company->name }}
                                        </div>
                                        <div class="text-sm text-slate-500">
                                            {{ $company->code }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($company->subscription_plan === 'Free')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                        <i data-lucide="package" class="h-3.5 w-3.5"></i>
                                        Free
                                    </span>
                                @elseif($company->subscription_plan === 'Premium Go')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                        <i data-lucide="zap" class="h-3.5 w-3.5"></i>
                                        Premium Go
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-100 px-3 py-1.5 text-xs font-semibold text-purple-700">
                                        <i data-lucide="crown" class="h-3.5 w-3.5"></i>
                                        {{ $company->subscription_plan }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-slate-800">
                                    {{ $company->max_employee }}
                                </span>
                                <span class="text-sm text-slate-500">
                                    karyawan
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($company->is_active)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-16 text-center text-sm text-slate-500">
                                <i data-lucide="building-2" class="mx-auto h-10 w-10 text-slate-300"></i>
                                <p class="mt-3">Belum ada company.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection