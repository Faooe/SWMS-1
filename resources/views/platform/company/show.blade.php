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

    @if(session('generated_password'))
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700"><i data-lucide="key-round" class="h-5 w-5"></i></div>
                <div class="min-w-0">
                    <h3 class="font-bold text-amber-800">Password Awal Super Administrator</h3>
                    <p class="mt-1 text-sm text-amber-700">Simpan sekarang. Password ini hanya ditampilkan satu kali.</p>
                    <div class="mt-3 inline-flex rounded-xl border border-amber-200 bg-white px-4 py-2.5 font-mono text-lg font-bold tracking-wider text-slate-900">{{ session('generated_password') }}</div>
                </div>
            </div>
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-5 px-5 py-5 sm:px-6 sm:py-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="min-w-0">
                <a href="{{ route('platform.companies.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-blue-700">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke Companies
                </a>

                <div class="mt-5 flex items-start gap-4">
                    @if($company->logo)
                        <img src="{{ secure_file_url($company->logo) }}" class="h-16 w-16 shrink-0 rounded-2xl border border-slate-200 object-cover">
                    @else
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-2xl font-bold text-blue-600">{{ strtoupper(substr($company->name, 0, 1)) }}</div>
                    @endif
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-500"><span>{{ $company->code }}</span><span>•</span><span>Tenant Company</span></div>
                        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $company->name }}</h1>
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full border {{ $company->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }} px-3 py-1.5 text-xs font-semibold">
                                <span class="h-1.5 w-1.5 rounded-full {{ $company->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $company->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <span class="inline-flex rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">{{ $company->subscription_plan }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex shrink-0 items-center gap-2">
                <a href="{{ route('platform.companies.edit', $company) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    <i data-lucide="square-pen" class="h-4 w-4"></i> Edit
                </a>
                <details class="relative">
                    <summary class="cursor-pointer list-none rounded-xl border border-slate-300 bg-white p-2.5 text-slate-600 hover:bg-slate-50 [&::-webkit-details-marker]:hidden"><i data-lucide="more-horizontal" class="h-5 w-5"></i></summary>
                    <div class="absolute right-0 z-20 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg">
                        @if($company->website)
                            <a href="{{ Str::startsWith($company->website, ['http://','https://']) ? $company->website : 'https://'.$company->website }}" target="_blank" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i data-lucide="globe" class="h-4 w-4"></i> Buka Website</a>
                        @endif
                        <form action="{{ route('platform.companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Hapus company ini?')">
                            @csrf @method('DELETE')
                            <button class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-semibold text-red-600 hover:bg-red-50"><i data-lucide="trash-2" class="h-4 w-4"></i> Hapus Company</button>
                        </form>
                    </div>
                </details>
            </div>
        </div>

        <div class="grid grid-cols-2 divide-x divide-y divide-slate-100 border-t border-slate-100 bg-slate-50/60 md:grid-cols-4 md:divide-y-0">
            <div class="px-5 py-4"><p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Employee</p><p class="mt-1 text-sm font-bold text-slate-800">{{ $company->employees_count }} / {{ $company->max_employee ?: '∞' }}</p><p class="text-xs text-slate-500">{{ $company->max_employee ? $employeeRatio.'% slot terpakai' : 'Tanpa batas' }}</p></div>
            <div class="px-5 py-4"><p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">User</p><p class="mt-1 text-sm font-bold text-slate-800">{{ $company->users_count }}</p><p class="text-xs text-slate-500">Akun terdaftar</p></div>
            <div class="px-5 py-4"><p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Office</p><p class="mt-1 text-sm font-bold text-slate-800">{{ $company->offices_count }}</p><p class="text-xs text-slate-500">Lokasi kerja</p></div>
            <div class="px-5 py-4"><p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Assignment</p><p class="mt-1 text-sm font-bold text-slate-800">{{ $company->assignments_count }}</p><p class="text-xs text-slate-500">Total penugasan</p></div>
        </div>
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
                        Informasi Company
                    </h3>
                </div>

                <div class="space-y-4">

                    <x-ui.detail-item icon="hash" label="Kode Company" :value="$company->code" />
                    <x-ui.detail-item icon="mail" label="Email" :value="$company->email" />
                    <x-ui.detail-item icon="phone" label="Telepon" :value="$company->phone" />
                    <x-ui.detail-item icon="globe" label="Situs Web" :value="$company->website" />

                </div>

            </x-ui.card>

            <x-ui.card>

                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100">
                        <i data-lucide="map-pin" class="h-5 w-5 text-emerald-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">
                        Alamat
                    </h3>
                </div>

                <div class="space-y-4">

                    <x-ui.detail-item icon="map" label="Alamat" :value="$company->address" />

                    <div class="grid grid-cols-2 gap-4">
                        <x-ui.detail-item icon="landmark" label="Kota" :value="$company->city" />
                        <x-ui.detail-item icon="flag" label="Provinsi" :value="$company->province" />
                    </div>

                    <x-ui.detail-item icon="mailbox" label="Kode Pos" :value="$company->postal_code" />

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
                                Lokasi Head Office
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
                            Buka di Maps
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
    {{-- Admin + Subscription & Billing + Aktivitas --}}
    {{-- ========================================================= --}}

    <div class="grid gap-6 lg:grid-cols-3">

        <x-ui.card>

            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100">
                    <i data-lucide="shield-check" class="h-5 w-5 text-purple-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">
                    Super Admin
                </h3>
            </div>

            @if($admin)

                <div class="space-y-4">

                    <x-ui.detail-item icon="user" label="Nama Lengkap" :value="$admin->employee?->full_name" />
                    <x-ui.detail-item icon="at-sign" label="Username" :value="$admin->username" />
                    <x-ui.detail-item icon="mail" label="Email" :value="$admin->email" />
                    <x-ui.detail-item icon="phone" label="Telepon" :value="$admin->employee?->phone" />
                    <x-ui.detail-item icon="log-in" label="Login Terakhir" :value="$admin->last_login_at" />

                </div>

            @else

                <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 py-10 text-center">
                    <i data-lucide="user-x" class="h-8 w-8 text-slate-300"></i>
                    <p class="mt-3 text-sm text-slate-500">
                        Super Admin belum tersedia.
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
                    Subscription & Billing
                </h3>
            </div>

            <div class="space-y-4">

                <x-ui.detail-item icon="package" label="Paket" :value="$company->subscription_plan" />
                <x-ui.detail-item icon="calendar" label="Mulai" :value="$company->subscription_start" />
                <x-ui.detail-item icon="calendar-x" label="Berakhir" :value="$company->subscription_end" />
                <x-ui.detail-item icon="users" label="Batas Employee" :value="$company->max_employee" />

            </div>

        </x-ui.card>

        <x-ui.card>

            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">
                    <i data-lucide="activity" class="h-5 w-5 text-slate-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">
                    Aktivitas
                </h3>
            </div>

            <div class="space-y-4">

                <x-ui.detail-item icon="calendar-plus" label="Dibuat" :value="$company->created_at" />
                <x-ui.detail-item icon="calendar-clock" label="Diperbarui" :value="$company->updated_at" />
                <x-ui.detail-item icon="badge-check" label="Status" :value="$company->is_active ? 'Aktif' : 'Nonaktif'" />

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