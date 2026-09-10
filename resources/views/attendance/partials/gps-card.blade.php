@php
    $hasCoordinates = filled($attendance->check_in_latitude) && filled($attendance->check_in_longitude);
    $distance = $attendance->check_in_distance;
    $radius = $attendance->allowed_radius;
    $polygon = $attendance->attendance_type === 'ASSIGNMENT' && is_array($attendance->assignment?->polygon) && count($attendance->assignment->polygon) >= 3
        ? $attendance->assignment->polygon
        : null;
    $usesPolygon = $polygon !== null;
    $isVerified = (bool) $attendance->location_verified;

    $checkInLat = $hasCoordinates ? (float) $attendance->check_in_latitude : null;
    $checkInLng = $hasCoordinates ? (float) $attendance->check_in_longitude : null;

    // Titik referensi validasi harus mengikuti sumber attendance saat dibuat.
    // Assignment memakai koordinat assignment; Office memakai koordinat office pada record attendance.
    $reference = $attendance->attendance_type === 'ASSIGNMENT' && $attendance->assignment
        ? $attendance->assignment
        : $attendance->office;

    $referenceLat = filled($reference?->latitude) ? (float) $reference->latitude : null;
    $referenceLng = filled($reference?->longitude) ? (float) $reference->longitude : null;
    $hasReference = $referenceLat !== null && $referenceLng !== null;
    $referenceName = $attendance->attendance_type === 'ASSIGNMENT'
        ? ($attendance->assignment?->title ?? 'Lokasi Assignment')
        : ($attendance->office?->name ?? 'Lokasi Office');

    $mapId = 'attendance-gps-map-' . $attendance->id;
@endphp

<x-ui.card>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i data-lucide="map-pin-check" class="h-5 w-5"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">GPS Validation</h2>
                <p class="mt-1 text-sm text-slate-500">Verifikasi posisi check in terhadap area kerja yang diizinkan.</p>
            </div>
        </div>

        <span class="inline-flex w-fit shrink-0 items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold {{ $isVerified ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
            <i data-lucide="{{ $isVerified ? 'badge-check' : 'circle-alert' }}" class="h-3.5 w-3.5"></i>
            {{ $isVerified ? 'Verified' : 'Not Verified' }}
        </span>
    </div>

    <div class="mt-5 grid gap-3 md:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-slate-50/70 px-4 py-3.5">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                <i data-lucide="navigation" class="h-3.5 w-3.5"></i>
                Employee Distance
            </div>
            <p class="mt-2 text-xl font-bold text-slate-900">{{ $distance !== null ? number_format($distance, 2) . ' m' : '-' }}</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-slate-50/70 px-4 py-3.5">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                <i data-lucide="circle-dashed" class="h-3.5 w-3.5"></i>
                {{ $usesPolygon ? 'Geofence Method' : 'Allowed Radius' }}
            </div>
            <p class="mt-2 text-xl font-bold text-slate-900">{{ $usesPolygon ? 'Area Polygon' : ($radius !== null ? $radius . ' m' : '-') }}</p>
        </div>

        <div class="rounded-xl border px-4 py-3.5 {{ $isVerified ? 'border-emerald-200 bg-emerald-50/60' : 'border-red-200 bg-red-50/60' }}">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide {{ $isVerified ? 'text-emerald-500' : 'text-red-500' }}">
                <i data-lucide="{{ $isVerified ? 'shield-check' : 'shield-alert' }}" class="h-3.5 w-3.5"></i>
                Result
            </div>
            <p class="mt-2 text-base font-bold {{ $isVerified ? 'text-emerald-700' : 'text-red-700' }}">
                {{ $usesPolygon ? ($isVerified ? 'Di dalam polygon' : 'Di luar polygon') : ($isVerified ? 'Dalam radius' : 'Di luar radius') }}
            </p>
        </div>
    </div>

    @if($hasCoordinates)
        <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-5">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                            <i data-lucide="map" class="h-4 w-4 text-blue-600"></i>
                            Peta Lokasi Check In
                        </div>
                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            Marker biru menunjukkan posisi employee. {{ $hasReference ? ($usesPolygon ? 'Area berwarna menunjukkan polygon lokasi kerja.' : 'Area lingkaran menunjukkan radius lokasi kerja.') : 'Titik referensi lokasi kerja tidak tersedia.' }}
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="https://www.google.com/maps?q={{ $attendance->check_in_latitude }},{{ $attendance->check_in_longitude }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">
                            <i data-lucide="external-link" class="h-3.5 w-3.5"></i>
                            Google Maps
                        </a>
                        <a href="https://www.openstreetmap.org/?mlat={{ $attendance->check_in_latitude }}&mlon={{ $attendance->check_in_longitude }}#map=18/{{ $attendance->check_in_latitude }}/{{ $attendance->check_in_longitude }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                            <i data-lucide="map-pinned" class="h-3.5 w-3.5"></i>
                            OpenStreetMap
                        </a>
                    </div>
                </div>
            </div>

            <div class="relative bg-slate-100">
                <div id="{{ $mapId }}" class="h-[300px] w-full sm:h-[340px]" aria-label="Peta lokasi attendance"></div>

                <div id="{{ $mapId }}-fallback" class="absolute inset-0 hidden items-center justify-center bg-slate-50 p-6 text-center">
                    <div>
                        <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                            <i data-lucide="map-off" class="h-5 w-5"></i>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-slate-700">Peta tidak dapat dimuat</p>
                        <p class="mt-1 text-xs text-slate-500">Koordinat tetap tersimpan. Gunakan tombol Google Maps atau OpenStreetMap.</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-0 border-t border-slate-200 sm:grid-cols-2">
                <div class="px-4 py-3 sm:px-5">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Koordinat Employee</p>
                    <p class="mt-1 font-mono text-xs text-slate-700">{{ $attendance->check_in_latitude }}, {{ $attendance->check_in_longitude }}</p>
                </div>
                <div class="border-t border-slate-200 px-4 py-3 sm:border-l sm:border-t-0 sm:px-5">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Lokasi Referensi</p>
                    <p class="mt-1 truncate text-xs font-medium text-slate-700">{{ $hasReference ? $referenceName : 'Tidak tersedia' }}</p>
                </div>
            </div>
        </div>
    @else
        <div class="mt-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
            <i data-lucide="map-pin-off" class="mt-0.5 h-5 w-5 shrink-0 text-amber-600"></i>
            <div>
                <p class="text-sm font-semibold text-amber-800">Koordinat check in tidak tersedia</p>
                <p class="mt-1 text-sm text-amber-700">Peta tidak dapat ditampilkan karena latitude/longitude tidak tersimpan.</p>
            </div>
        </div>
    @endif
</x-ui.card>

@if($hasCoordinates)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const mapElement = document.getElementById(@json($mapId));
                const fallbackElement = document.getElementById(@json($mapId . '-fallback'));

                if (!mapElement) return;

                try {
                    if (typeof L === 'undefined') {
                        throw new Error('Leaflet is unavailable');
                    }

                    const employeePoint = [@json($checkInLat), @json($checkInLng)];
                    const map = L.map(mapElement, {
                        zoomControl: true,
                        scrollWheelZoom: false,
                        preferCanvas: false,
                    }).setView(employeePoint, 18);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors',
                    }).addTo(map);

                    const employeeMarker = L.circleMarker(employeePoint, {
                        radius: 8,
                        weight: 3,
                        color: '#ffffff',
                        fillColor: '#2563eb',
                        fillOpacity: 1,
                    }).addTo(map).bindPopup('<strong>Posisi Check In</strong><br>Employee');

                    const bounds = L.latLngBounds([employeePoint]);

                    @if($hasReference)
                        const referencePoint = [@json($referenceLat), @json($referenceLng)];
                        const referenceMarker = L.circleMarker(referencePoint, {
                            radius: 7,
                            weight: 2,
                            color: '#059669',
                            fillColor: '#10b981',
                            fillOpacity: 1,
                        }).addTo(map).bindPopup(@json('<strong>' . e($referenceName) . '</strong><br>Lokasi referensi'));

                        @if($usesPolygon)
                            const polygonPoints = @json($polygon);
                            const polygonLayer = L.polygon(polygonPoints, {
                                color: '#d97706',
                                weight: 2,
                                opacity: 0.9,
                                fillColor: '#fbbf24',
                                fillOpacity: 0.16,
                            }).addTo(map);
                            polygonLayer.getBounds().getNorthEast() && bounds.extend(polygonLayer.getBounds());
                        @elseif($radius !== null)
                            L.circle(referencePoint, {
                                radius: @json((float) $radius),
                                color: '#10b981',
                                weight: 2,
                                opacity: 0.8,
                                fillColor: '#10b981',
                                fillOpacity: 0.10,
                            }).addTo(map);
                        @endif

                        L.polyline([referencePoint, employeePoint], {
                            color: @json($isVerified ? '#10b981' : '#ef4444'),
                            weight: 2,
                            opacity: 0.8,
                            dashArray: '6,6',
                        }).addTo(map);

                        bounds.extend(referencePoint);
                        map.fitBounds(bounds.pad(0.45), { maxZoom: 18 });
                    @else
                        employeeMarker.openPopup();
                    @endif

                    setTimeout(() => map.invalidateSize(), 120);
                } catch (error) {
                    console.warn('Attendance map could not be initialized:', error);
                    if (fallbackElement) {
                        fallbackElement.classList.remove('hidden');
                        fallbackElement.classList.add('flex');
                    }
                }
            });
        </script>
    @endpush
@endif
