@extends('layouts.app')
@section('title', 'Company Detail')
@section('content')
<x-platform.company-created-modal />
@php
    $headOffice = $company->offices->firstWhere('is_head_office', true) ?? $company->offices->first();
    $hasLocation = $headOffice && $headOffice->latitude && $headOffice->longitude;
    $mapId = 'company-detail-map-' . $company->id;
    $admin = $company->users->firstWhere('role.code', 'SUPER_ADMIN');
    $employeeRatio = $company->max_employee > 0 ? round(($company->employees_count / $company->max_employee) * 100) : 0;
@endphp

<div class="space-y-5">
    @if(session('generated_password'))
        <div class="flex items-start gap-3 rounded-2xl border border-amber-100 bg-amber-50 px-4 py-3"><div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-amber-600 ring-1 ring-amber-100"><i data-lucide="key-round" class="h-4 w-4"></i></div><div><p class="text-sm font-bold text-slate-800">Password awal Super Administrator</p><p class="mt-0.5 text-xs text-slate-600">Simpan sekarang. Password ini hanya ditampilkan satu kali.</p><code class="mt-2 inline-flex rounded-lg bg-white px-3 py-1.5 text-sm font-black text-slate-900 ring-1 ring-amber-100">{{ session('generated_password') }}</code></div></div>
    @endif

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-5 px-5 py-5 sm:px-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="flex min-w-0 items-start gap-4">
                <a href="{{ route('platform.companies.index') }}" class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition hover:bg-slate-200"><i data-lucide="arrow-left" class="h-5 w-5"></i></a>
                @if($company->logo)<img src="{{ secure_file_url($company->logo) }}" class="h-16 w-16 shrink-0 rounded-2xl border border-slate-200 object-cover">@else<div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-2xl font-black text-blue-600">{{ strtoupper(substr($company->name,0,1)) }}</div>@endif
                <div class="min-w-0"><div class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-[0.1em] text-blue-600"><span>Company Detail</span><span class="text-slate-300">•</span><span class="text-slate-400">{{ $company->code }}</span></div><h1 class="mt-1 truncate text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">{{ $company->name }}</h1><div class="mt-3 flex flex-wrap items-center gap-2"><span class="inline-flex items-center gap-1.5 rounded-full border {{ $company->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }} px-3 py-1.5 text-xs font-bold"><span class="h-1.5 w-1.5 rounded-full {{ $company->is_active ? 'bg-emerald-500':'bg-slate-400' }}"></span>{{ $company->is_active ? 'Aktif':'Nonaktif' }}</span><span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700">{{ $company->subscription_plan }}</span></div></div>
            </div>
            <div class="flex shrink-0 items-center gap-2"><a href="{{ route('platform.companies.edit',$company) }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700"><i data-lucide="square-pen" class="h-4 w-4"></i>Edit Company</a><details class="relative"><summary class="cursor-pointer list-none rounded-xl border border-slate-200 bg-white p-2.5 text-slate-500 hover:bg-slate-50 [&::-webkit-details-marker]:hidden"><i data-lucide="more-horizontal" class="h-5 w-5"></i></summary><div class="absolute right-0 z-20 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg">@if($company->website)<a href="{{ Str::startsWith($company->website,['http://','https://']) ? $company->website : 'https://'.$company->website }}" target="_blank" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i data-lucide="globe" class="h-4 w-4"></i>Buka Website</a>@endif<form action="{{ route('platform.companies.destroy',$company) }}" method="POST" onsubmit="return confirm('Hapus company ini?')">@csrf @method('DELETE')<button class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-semibold text-rose-600 hover:bg-rose-50"><i data-lucide="trash-2" class="h-4 w-4"></i>Hapus Company</button></form></div></details></div>
        </div>
        <div class="grid grid-cols-2 divide-x divide-y divide-slate-100 border-t border-slate-100 sm:grid-cols-4 sm:divide-y-0">
            @foreach([
                ['Employee', $company->employees_count.' / '.($company->max_employee ?: '∞'), $company->max_employee ? $employeeRatio.'% terpakai' : 'Tanpa batas', 'users', 'bg-blue-50 text-blue-600'],
                ['User', $company->users_count, 'Akun terdaftar', 'user-round', 'bg-violet-50 text-violet-600'],
                ['Office', $company->offices_count, 'Lokasi kerja', 'map-pin', 'bg-emerald-50 text-emerald-600'],
                ['Assignment', $company->assignments_count, 'Total penugasan', 'clipboard-list', 'bg-amber-50 text-amber-600'],
            ] as [$label,$value,$caption,$icon,$tone])
                <div class="flex items-center gap-3 px-4 py-4 sm:px-5"><div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $tone }}"><i data-lucide="{{ $icon }}" class="h-5 w-5"></i></div><div><p class="text-lg font-black leading-none text-slate-900">{{ $value }}</p><p class="mt-1 text-xs font-bold text-slate-500">{{ $label }}</p><p class="mt-0.5 text-[11px] text-slate-400">{{ $caption }}</p></div></div>
            @endforeach
        </div>
    </section>

    <div class="grid items-start gap-5 xl:grid-cols-[minmax(0,1fr)_360px]">
        <div class="min-w-0 space-y-5">
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="building-2" class="h-5 w-5"></i></span><div><h2 class="text-base font-black text-slate-900">Informasi Company</h2><p class="mt-0.5 text-xs text-slate-500">Identitas dan kontak utama tenant.</p></div></div>
                <div class="grid gap-x-6 gap-y-5 p-5 sm:grid-cols-2 sm:p-6">
                    <x-ui.detail-item icon="hash" label="Kode Company" :value="$company->code" /><x-ui.detail-item icon="mail" label="Email" :value="$company->email" /><x-ui.detail-item icon="phone" label="Telepon" :value="$company->phone" /><x-ui.detail-item icon="globe" label="Situs Web" :value="$company->website" />
                </div>
            </section>

            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between"><div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><i data-lucide="map-pin" class="h-5 w-5"></i></span><div><h2 class="text-base font-black text-slate-900">Head Office</h2><p class="mt-0.5 text-xs text-slate-500">{{ $headOffice->name ?? 'Belum ada kantor terdaftar' }}</p></div></div>@if($hasLocation)<a href="https://maps.google.com/?q={{ $headOffice->latitude }},{{ $headOffice->longitude }}" target="_blank" class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50"><i data-lucide="external-link" class="h-3.5 w-3.5"></i>Buka Maps</a>@endif</div>
                <div class="p-5 sm:p-6">@if($hasLocation)<div id="{{ $mapId }}" class="h-72 w-full overflow-hidden rounded-2xl border border-slate-200 bg-slate-50"></div><div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4"><div class="rounded-xl bg-slate-50 px-3 py-3"><p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Latitude</p><p class="mt-1 truncate text-xs font-bold text-slate-700">{{ $headOffice->latitude }}</p></div><div class="rounded-xl bg-slate-50 px-3 py-3"><p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Longitude</p><p class="mt-1 truncate text-xs font-bold text-slate-700">{{ $headOffice->longitude }}</p></div><div class="rounded-xl bg-blue-50 px-3 py-3"><p class="text-[10px] font-bold uppercase tracking-wide text-blue-400">Radius</p><p class="mt-1 text-xs font-bold text-blue-700">{{ number_format($headOffice->radius ?? 0) }} m</p></div><div class="rounded-xl bg-slate-50 px-3 py-3"><p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Timezone</p><p class="mt-1 truncate text-xs font-bold text-slate-700">{{ $headOffice->timezone ?? '-' }}</p></div></div>@else<div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center"><div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-white text-slate-300"><i data-lucide="map-pin-off" class="h-6 w-6"></i></div><p class="mt-3 text-sm font-black text-slate-700">Lokasi belum tersedia</p><p class="mt-1 text-xs text-slate-500">Tambahkan koordinat Head Office melalui Edit Company.</p></div>@endif</div>
            </section>

            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"><div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600"><i data-lucide="map" class="h-5 w-5"></i></span><div><h2 class="text-base font-black text-slate-900">Alamat Company</h2><p class="mt-0.5 text-xs text-slate-500">Alamat administratif tenant.</p></div></div><div class="grid gap-x-6 gap-y-5 p-5 sm:grid-cols-2 sm:p-6"><div class="sm:col-span-2"><x-ui.detail-item icon="map" label="Alamat" :value="$company->address" /></div><x-ui.detail-item icon="landmark" label="Kota" :value="$company->city" /><x-ui.detail-item icon="flag" label="Provinsi" :value="$company->province" /><x-ui.detail-item icon="mailbox" label="Kode Pos" :value="$company->postal_code" /></div></section>
        </div>

        <aside class="space-y-5 xl:sticky xl:top-6">
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"><div class="border-b border-slate-100 px-5 py-4"><h2 class="text-sm font-black text-slate-900">Super Administrator</h2><p class="mt-0.5 text-xs text-slate-500">Akun utama company.</p></div><div class="p-5">@if($admin)<div class="mb-4 flex items-center gap-3"><div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600"><i data-lucide="shield-check" class="h-5 w-5"></i></div><div class="min-w-0"><p class="truncate text-sm font-black text-slate-900">{{ $admin->employee?->full_name ?: $admin->username }}</p><p class="truncate text-xs text-slate-400">{{ $admin->email }}</p></div></div><div class="space-y-4"><x-ui.detail-item icon="at-sign" label="Username" :value="$admin->username" /><x-ui.detail-item icon="phone" label="Telepon" :value="$admin->employee?->phone" /><x-ui.detail-item icon="log-in" label="Login Terakhir" :value="$admin->last_login_at" /></div>@else<div class="rounded-2xl border border-dashed border-slate-200 py-8 text-center text-sm text-slate-400">Super Admin belum tersedia.</div>@endif</div></section>

            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"><div class="border-b border-slate-100 px-5 py-4"><div class="flex items-center justify-between"><div><h2 class="text-sm font-black text-slate-900">Subscription</h2><p class="mt-0.5 text-xs text-slate-500">Paket dan kapasitas tenant.</p></div><span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-700">{{ $company->subscription_plan }}</span></div></div><div class="space-y-4 p-5"><x-ui.detail-item icon="calendar" label="Mulai" :value="$company->subscription_start" /><x-ui.detail-item icon="calendar-x" label="Berakhir" :value="$company->subscription_end" /><x-ui.detail-item icon="users" label="Batas Employee" :value="$company->max_employee" /></div></section>

            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"><div class="border-b border-slate-100 px-5 py-4"><h2 class="text-sm font-black text-slate-900">Aktivitas Data</h2><p class="mt-0.5 text-xs text-slate-500">Jejak pembaruan company.</p></div><div class="space-y-4 p-5"><x-ui.detail-item icon="calendar-plus" label="Dibuat" :value="$company->created_at" /><x-ui.detail-item icon="calendar-clock" label="Diperbarui" :value="$company->updated_at" /></div></section>
        </aside>
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
