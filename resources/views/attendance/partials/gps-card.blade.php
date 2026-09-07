@php
    $hasCoordinates = filled($attendance->check_in_latitude) && filled($attendance->check_in_longitude);
    $distance = $attendance->check_in_distance;
    $radius = $attendance->allowed_radius;
    $isVerified = (bool) $attendance->location_verified;
@endphp

<x-ui.card>
    <div class="mb-5 flex items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i data-lucide="map-pin-check" class="h-5 w-5"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">GPS Validation</h2>
                <p class="mt-1 text-sm text-slate-500">Validasi lokasi saat employee melakukan check in.</p>
            </div>
        </div>

        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold {{ $isVerified ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
            <i data-lucide="{{ $isVerified ? 'badge-check' : 'circle-alert' }}" class="h-3.5 w-3.5"></i>
            {{ $isVerified ? 'Verified' : 'Not Verified' }}
        </span>
    </div>

    <div class="grid gap-3 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Employee Distance</p>
            <p class="mt-2 text-xl font-bold text-slate-900">{{ $distance !== null ? number_format($distance, 2) . ' m' : '-' }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Allowed Radius</p>
            <p class="mt-2 text-xl font-bold text-slate-900">{{ $radius !== null ? $radius . ' m' : '-' }}</p>
        </div>
        <div class="rounded-xl border p-4 {{ $isVerified ? 'border-emerald-200 bg-emerald-50/60' : 'border-red-200 bg-red-50/60' }}">
            <p class="text-xs font-semibold uppercase tracking-wide {{ $isVerified ? 'text-emerald-500' : 'text-red-500' }}">Result</p>
            <p class="mt-2 text-base font-bold {{ $isVerified ? 'text-emerald-700' : 'text-red-700' }}">{{ $isVerified ? 'Dalam radius' : 'Di luar radius' }}</p>
        </div>
    </div>

    @if($hasCoordinates)
        <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                        <i data-lucide="navigation" class="h-4 w-4 text-blue-600"></i>
                        Lokasi Check In
                    </div>
                    <p class="mt-1 text-sm text-slate-500">Koordinat tersimpan dari perangkat employee.</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="rounded-lg bg-slate-100 px-3 py-2 font-mono text-xs text-slate-700">Lat {{ $attendance->check_in_latitude }}</span>
                        <span class="rounded-lg bg-slate-100 px-3 py-2 font-mono text-xs text-slate-700">Lng {{ $attendance->check_in_longitude }}</span>
                    </div>
                </div>

                <div class="flex shrink-0 flex-wrap gap-2">
                    <a href="https://www.google.com/maps?q={{ $attendance->check_in_latitude }},{{ $attendance->check_in_longitude }}"
                       target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                        <i data-lucide="map" class="h-4 w-4"></i>
                        Google Maps
                    </a>
                    <a href="https://www.openstreetmap.org/?mlat={{ $attendance->check_in_latitude }}&mlon={{ $attendance->check_in_longitude }}#map=18/{{ $attendance->check_in_latitude }}/{{ $attendance->check_in_longitude }}"
                       target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        <i data-lucide="external-link" class="h-4 w-4"></i>
                        OpenStreetMap
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="mt-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
            <i data-lucide="map-pin-off" class="mt-0.5 h-5 w-5 shrink-0 text-amber-600"></i>
            <div>
                <p class="text-sm font-semibold text-amber-800">Koordinat check in tidak tersedia</p>
                <p class="mt-1 text-sm text-amber-700">Lokasi tidak dapat dibuka karena latitude/longitude tidak tersimpan.</p>
            </div>
        </div>
    @endif
</x-ui.card>
