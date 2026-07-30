@extends('layouts.app')

@section('title', 'Company Detail')

@section('content')
<x-platform.company-created-modal />

@php
    $headOffice = $company->offices->firstWhere('is_head_office', true)
        ?? $company->offices->first();

    $hasLocation = $headOffice && $headOffice->latitude && $headOffice->longitude;

    $mapId = 'company-detail-map-' . $company->id;

    $admin = $company->users->firstWhere('role.code', 'SUPER_ADMIN');

    $employeeRatio = $company->max_employee > 0
        ? round(($company->employees_count / $company->max_employee) * 100)
        : 0;
@endphp

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <x-ui.page-header
        title="Company Detail"
        description="Informasi lengkap tenant perusahaan.">

        <div class="flex gap-3">

            <a href="{{ route('platform.companies.index') }}">
                <x-ui.button variant="secondary" class="!rounded-full !shadow-none hover:!bg-slate-200">
                    <i data-lucide="chevron-left" class="h-4 w-4"></i>
                    Back
                </x-ui.button>
            </a>

            <a href="{{ route('platform.companies.edit', $company) }}">
                <x-ui.button class="!rounded-full !shadow-sm hover:!shadow-md hover:!-translate-y-0.5">
                    <i data-lucide="pencil-line" class="h-4 w-4"></i>
                    Edit
                </x-ui.button>
            </a>

            <form
                action="{{ route('platform.companies.destroy', $company) }}"
                method="POST"
                onsubmit="return confirm('Delete this company?')">
                @csrf
                @method('DELETE')
                <x-ui.button type="submit" variant="danger" class="!rounded-full !shadow-sm hover:!shadow-md hover:!-translate-y-0.5">
                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                    Delete
                </x-ui.button>
            </form>

        </div>

    </x-ui.page-header>

    {{-- ========================================================= --}}
    {{-- Password Alert --}}
    {{-- ========================================================= --}}

    @if(session('generated_password'))

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6">

            <div class="flex gap-4">

                <i data-lucide="triangle-alert" class="mt-1 h-6 w-6 shrink-0 text-amber-600"></i>

                <div>

                    <h3 class="font-bold text-amber-700">
                        Password Awal Super Administrator
                    </h3>

                    <p class="mt-2 text-sm text-amber-700">
                        Password ini hanya ditampilkan satu kali.
                    </p>

                    <div class="mt-4 rounded-xl bg-white px-5 py-3 font-mono text-xl font-bold tracking-wider">
                        {{ session('generated_password') }}
                    </div>

                </div>

            </div>

        </div>

    @endif

    {{-- ========================================================= --}}
    {{-- Hero --}}
    {{-- ========================================================= --}}

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <div class="h-28 border-b border-slate-100 bg-slate-50"></div>

        <div class="flex flex-col gap-6 px-6 pb-6 md:flex-row md:items-end">

            <div class="-mt-14 shrink-0">

                @if($company->logo)

                    <img
                        src="{{ asset('storage/'.$company->logo) }}"
                        class="h-28 w-28 rounded-3xl border-4 border-white object-cover shadow-md">

                @else

                    <div class="flex h-28 w-28 items-center justify-center rounded-3xl border-4 border-white bg-slate-100 text-4xl font-bold text-slate-700 shadow-md">
                        {{ strtoupper(substr($company->name, 0, 1)) }}
                    </div>

                @endif

            </div>

            <div class="flex-1 pt-3 md:pt-0">

                <div class="flex flex-wrap items-center gap-3">

                    <h2 class="text-3xl font-bold text-slate-800">
                        {{ $company->name }}
                    </h2>

                    @if($company->is_active)
                        <x-ui.badge color="green">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                Active
                            </span>
                        </x-ui.badge>
                    @else
                        <x-ui.badge color="red">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>
                                Inactive
                            </span>
                        </x-ui.badge>
                    @endif

                    <x-ui.badge color="blue">
                        {{ $company->subscription_plan }}
                    </x-ui.badge>

                </div>

                <p class="mt-1.5 flex items-center gap-1.5 text-slate-500">
                    <i data-lucide="hash" class="h-4 w-4"></i>
                    {{ $company->code }}
                </p>

            </div>

            <div class="flex shrink-0 flex-wrap gap-2 pt-3 md:pt-0">

                @if($company->website)
                    
                        href="{{ Str::startsWith($company->website, ['http://','https://']) ? $company->website : 'https://'.$company->website }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        <i data-lucide="globe" class="h-4 w-4"></i>
                        Website
                    </a>
                @endif

                @if($company->email)
                    
                        href="mailto:{{ $company->email }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        <i data-lucide="mail" class="h-4 w-4"></i>
                        Email
                    </a>
                @endif

            </div>

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- Statistics --}}
    {{-- ========================================================= --}}

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">

        <x-ui.stat-card
            title="Employees"
            :value="$company->employees_count"
            icon="users"
            color="blue"
            :description="$company->max_employee ? $employeeRatio.'% of '.$company->max_employee.' slots used' : null" />

        <x-ui.stat-card
            title="Users"
            :value="$company->users_count"
            icon="user-cog"
            color="purple" />

        <x-ui.stat-card
            title="Offices"
            :value="$company->offices_count"
            icon="building"
            color="emerald" />

        <x-ui.stat-card
            title="Assignments"
            :value="$company->assignments_count"
            icon="clipboard-list"
            color="amber" />

    </div>

    {{-- ========================================================= --}}
    {{-- Information + Map --}}
    {{-- ========================================================= --}}

    <div class="grid gap-6 lg:grid-cols-5">

        {{-- Left column: info stack --}}
        <div class="space-y-6 lg:col-span-2">

            <x-ui.card>

                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">
                        <i data-lucide="building-2" class="h-5 w-5 text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">
                        Company Information
                    </h3>
                </div>

                <div class="space-y-4">

                    <x-ui.detail-item icon="hash" label="Company Code" :value="$company->code" />
                    <x-ui.detail-item icon="mail" label="Email" :value="$company->email" />
                    <x-ui.detail-item icon="phone" label="Phone" :value="$company->phone" />
                    <x-ui.detail-item icon="globe" label="Website" :value="$company->website" />

                </div>

            </x-ui.card>

            <x-ui.card>

                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100">
                        <i data-lucide="map-pin" class="h-5 w-5 text-emerald-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">
                        Address
                    </h3>
                </div>

                <div class="space-y-4">

                    <x-ui.detail-item icon="map" label="Address" :value="$company->address" />

                    <div class="grid grid-cols-2 gap-4">
                        <x-ui.detail-item icon="landmark" label="City" :value="$company->city" />
                        <x-ui.detail-item icon="flag" label="Province" :value="$company->province" />
                    </div>

                    <x-ui.detail-item icon="mailbox" label="Postal Code" :value="$company->postal_code" />

                </div>

            </x-ui.card>

        </div>

        {{-- Right column: map --}}
        <div class="lg:col-span-3">

            <x-ui.card class="flex h-full flex-col">

                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">

                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100">
                            <i data-lucide="building" class="h-5 w-5 text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">
                                Head Office Location
                            </h3>
                            <p class="text-sm text-slate-500">
                                {{ $headOffice->name ?? 'Belum ada kantor terdaftar' }}
                            </p>
                        </div>
                    </div>

                    @if($hasLocation)
                        <a
                            href="https://maps.google.com/?q={{ $headOffice->latitude }},{{ $headOffice->longitude }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            <i data-lucide="external-link" class="h-4 w-4"></i>
                            Open in Maps
                        </a>
                    @endif

                </div>

                @if($hasLocation)

                    <div
                        id="{{ $mapId }}"
                        class="h-[280px] w-full overflow-hidden rounded-2xl border border-slate-200 shadow-inner">
                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-3">

                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Latitude</p>
                            <p class="mt-1 truncate font-semibold text-slate-800">{{ $headOffice->latitude }}</p>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Longitude</p>
                            <p class="mt-1 truncate font-semibold text-slate-800">{{ $headOffice->longitude }}</p>
                        </div>

                        <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-blue-500">Radius</p>
                            <p class="mt-1 truncate font-semibold text-blue-700">{{ number_format($headOffice->radius ?? 0) }} m</p>
                        </div>

                    </div>

                    <div class="mt-4 flex items-center gap-3 text-sm text-slate-500">
                        <i data-lucide="clock" class="h-4 w-4 shrink-0"></i>
                        Timezone: <span class="font-semibold text-slate-700">{{ $headOffice->timezone ?? '-' }}</span>
                    </div>

                @else

                    <div class="flex flex-1 flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50 py-16 text-center">

                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                            <i data-lucide="map-pin-off" class="h-8 w-8 text-slate-400"></i>
                        </div>

                        <h4 class="mt-4 font-bold text-slate-700">
                            Belum Ada Titik Lokasi
                        </h4>

                        <p class="mt-1 max-w-xs text-sm text-slate-500">
                            Tambahkan kantor pusat beserta koordinatnya agar lokasi company muncul di peta.
                        </p>

                    </div>

                @endif

            </x-ui.card>

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- Admin + Subscription + Activity --}}
    {{-- ========================================================= --}}

    <div class="grid gap-6 lg:grid-cols-3">

        <x-ui.card>

            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100">
                    <i data-lucide="shield-check" class="h-5 w-5 text-purple-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">
                    Super Administrator
                </h3>
            </div>

            @if($admin)

                <div class="space-y-4">

                    <x-ui.detail-item icon="user" label="Full Name" :value="$admin->employee?->full_name" />
                    <x-ui.detail-item icon="at-sign" label="Username" :value="$admin->username" />
                    <x-ui.detail-item icon="mail" label="Email" :value="$admin->email" />
                    <x-ui.detail-item icon="phone" label="Phone" :value="$admin->employee?->phone" />
                    <x-ui.detail-item icon="log-in" label="Last Login" :value="$admin->last_login_at" />

                </div>

            @else

                <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 py-10 text-center">
                    <i data-lucide="user-x" class="h-8 w-8 text-slate-300"></i>
                    <p class="mt-3 text-sm text-slate-500">
                        Super Administrator belum tersedia.
                    </p>
                </div>

            @endif

        </x-ui.card>

        <x-ui.card>

            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100">
                    <i data-lucide="gem" class="h-5 w-5 text-amber-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">
                    Subscription
                </h3>
            </div>

            <div class="space-y-4">

                <x-ui.detail-item icon="package" label="Plan" :value="$company->subscription_plan" />
                <x-ui.detail-item icon="calendar" label="Start" :value="$company->subscription_start" />
                <x-ui.detail-item icon="calendar-x" label="Expired" :value="$company->subscription_end" />
                <x-ui.detail-item icon="users" label="Employee Limit" :value="$company->max_employee" />

            </div>

        </x-ui.card>

        <x-ui.card>

            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">
                    <i data-lucide="activity" class="h-5 w-5 text-slate-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">
                    Activity
                </h3>
            </div>

            <div class="space-y-4">

                <x-ui.detail-item icon="calendar-plus" label="Created At" :value="$company->created_at" />
                <x-ui.detail-item icon="calendar-clock" label="Updated At" :value="$company->updated_at" />
                <x-ui.detail-item icon="badge-check" label="Status" :value="$company->is_active ? 'Active' : 'Inactive'" />

            </div>

        </x-ui.card>

    </div>

</div>

@if($hasLocation)

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {

        const mapElement = document.getElementById('{{ $mapId }}');

        if (!mapElement || typeof L === 'undefined') {
            return;
        }

        const lat = {{ $headOffice->latitude }};
        const lng = {{ $headOffice->longitude }};
        const radius = {{ $headOffice->radius ?? 200 }};

        const map = L.map('{{ $mapId }}', {
            zoomControl: true,
            scrollWheelZoom: false,
        }).setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap',
        }).addTo(map);

        L.control.scale({
            metric: true,
            imperial: false,
        }).addTo(map);

        L.marker([lat, lng])
            .addTo(map)
            .bindPopup('<b>{{ addslashes($headOffice->name ?? $company->name) }}</b><br>Head Office')
            .openPopup();

        L.circle([lat, lng], {
            radius: radius,
            color: '#4f46e5',
            fillColor: '#6366f1',
            fillOpacity: .15,
            weight: 2,
        }).addTo(map);

        setTimeout(() => map.invalidateSize(), 200);

        if (window.lucide) {
            lucide.createIcons();
        }

    });
    </script>
    @endpush

@endif

@endsection