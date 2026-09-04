@extends('layouts.app')

@section('title', 'Attendance')
@section('page-title', 'Attendance')

@section('content')
@php
    $present = (int) ($summary['present'] ?? 0);
    $late = (int) ($summary['late'] ?? 0);
    $leavePermission = (int) (($summary['leave'] ?? 0) + ($summary['permission'] ?? 0));
    $absent = (int) ($summary['absent'] ?? 0);
    $totalRecorded = $present + $late + $leavePermission + $absent;
    $attendanceRate = $totalRecorded > 0
        ? (int) round((($present + $late) / $totalRecorded) * 100)
        : 0;
@endphp

<div class="mx-auto max-w-7xl space-y-5">
    @include('employee.attendance.partials.today-status')

    <x-ui.card class="overflow-hidden p-0">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">Hari Ini</p>
                <h3 class="mt-1 text-xl font-bold text-slate-900">Workspace Attendance</h3>
                <p class="mt-1 text-sm text-slate-500">Check In/Out office dan lihat konteks assignment hari ini dalam satu tempat.</p>
            </div>
            <a href="{{ route('employee.attendance.history') }}" class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-700">
                <i data-lucide="history" class="h-4 w-4"></i>
                Riwayat Attendance
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.35fr)_minmax(320px,.65fr)]">
            @include('employee.attendance.partials.office-card')
            @include('employee.attendance.partials.assignment-card')
        </div>
    </x-ui.card>

    <x-ui.card class="overflow-hidden p-0">
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(260px,.7fr)_minmax(0,1.3fr)]">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-6 lg:border-b-0 lg:border-r">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">Bulan Ini</p>
                <div class="mt-1 flex items-end justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Ringkasan Kehadiran</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $totalRecorded }} hari attendance tercatat.</p>
                    </div>
                    <span class="text-2xl font-bold text-blue-600">{{ $attendanceRate }}%</span>
                </div>
                <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-blue-600" style="width: {{ $attendanceRate }}%"></div>
                </div>
                <p class="mt-3 text-xs leading-5 text-slate-400">Persentase dihitung dari hari Hadir + Terlambat dibanding seluruh attendance yang tercatat pada bulan berjalan.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4">
                @foreach([
                    ['label' => 'Hadir', 'value' => $present],
                    ['label' => 'Terlambat', 'value' => $late],
                    ['label' => 'Cuti / Izin', 'value' => $leavePermission],
                    ['label' => 'Tidak Hadir', 'value' => $absent],
                ] as $item)
                    <div class="px-5 py-5 sm:px-6 {{ !$loop->last ? 'border-r border-slate-100' : '' }} {{ $loop->index < 2 ? 'border-b border-slate-100 sm:border-b-0' : '' }}">
                        <p class="text-xs font-medium text-slate-400">{{ $item['label'] }}</p>
                        <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ $item['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </x-ui.card>
</div>

@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    const csrfToken = document

        .querySelector('meta[name="csrf-token"]')

        ?.content;

    /*
    |--------------------------------------------------------------------------
    | Haversine (Client Side, hanya untuk tampilan)
    |--------------------------------------------------------------------------
    */

    function haversine(lat1, lon1, lat2, lon2) {

        const R = 6371000;

        const dLat = (lat2 - lat1) * Math.PI / 180;

        const dLon = (lon2 - lon1) * Math.PI / 180;

        const a =
            Math.sin(dLat / 2) ** 2 +
            Math.cos(lat1 * Math.PI / 180) *
            Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) ** 2;

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c;

    }

    /*
    |--------------------------------------------------------------------------
    | Office Card
    |--------------------------------------------------------------------------
    */

    const officeCard = document.getElementById('office-card');

    if (officeCard) {

        const officeLat = parseFloat(officeCard.dataset.officeLat);

        const officeLng = parseFloat(officeCard.dataset.officeLng);

        const officeRadius = parseInt(officeCard.dataset.officeRadius);

        let officePolygon = null;

        try {

            officePolygon = officeCard.dataset.officePolygon

                ? JSON.parse(officeCard.dataset.officePolygon)

                : null;

        } catch (e) {

            officePolygon = null;

        }

        const currentLatEl = document.getElementById('current-lat');
        const currentLngEl = document.getElementById('current-lng');
        const distanceEl = document.getElementById('office-distance');
        const statusEl = document.getElementById('office-location-status');

        const checkInBtn = document.getElementById('office-check-in-btn');
        const checkOutBtn = document.getElementById('office-check-out-btn');

        let currentLat = null;
        let currentLng = null;

        let map = null;
        let marker = null;
        let circle = null;
        let polygonLayer = null;

        /*
        |--------------------------------------------------------------------------
        | Init Map
        |--------------------------------------------------------------------------
        */

        if (!isNaN(officeLat) && !isNaN(officeLng)) {

            map = L.map('office-mini-map', {

                zoomControl: false,

                dragging: false,

                scrollWheelZoom: false,

            }).setView([officeLat, officeLng], 16);

           L.tileLayer(

                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

                { maxZoom: 19, attribution: '&copy; OpenStreetMap' }

            ).addTo(map);
            setTimeout(() => {

            map.invalidateSize();

            }, 100);

            if (officePolygon && officePolygon.length >= 3) {

                const latlngs = officePolygon.map((point) => [point[0], point[1]]);

                polygonLayer = L.polygon(latlngs, {

                    color: '#f59e0b',

                    fillColor: '#fbbf24',

                    fillOpacity: .25,

                    weight: 2,

                }).addTo(map);

                map.fitBounds(polygonLayer.getBounds(), { padding: [20, 20] });

            } else {

                circle = L.circle(

                    [officeLat, officeLng],

                    {
                        radius: officeRadius,
                        color: '#2563eb',
                        fillColor: '#3b82f6',
                        fillOpacity: .15,
                    }

                ).addTo(map);

            }

            L.marker([officeLat, officeLng])

                .bindPopup('Office')

                .addTo(map);

        }

        /*
        |--------------------------------------------------------------------------
        | Point In Polygon (Ray Casting, sama seperti backend)
        |--------------------------------------------------------------------------
        */

        function isPointInPolygon(lat, lng, polygon) {

            let inside = false;

            for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {

                const latI = polygon[i][0], lngI = polygon[i][1];
                const latJ = polygon[j][0], lngJ = polygon[j][1];

                const intersects =

                    ((lngI > lng) !== (lngJ > lng)) &&

                    (lat < (latJ - latI) * (lng - lngI) / (lngJ - lngI) + latI);

                if (intersects) inside = !inside;

            }

            return inside;

        }

        /*
        |--------------------------------------------------------------------------
        | Watch Location
        |--------------------------------------------------------------------------
        */

        function updateLocation(position) {

            currentLat = position.coords.latitude;
            currentLng = position.coords.longitude;

            if (currentLatEl) currentLatEl.textContent = currentLat.toFixed(7);
            if (currentLngEl) currentLngEl.textContent = currentLng.toFixed(7);

           if (!isNaN(officeLat) && !isNaN(officeLng)) {

                const distance = haversine(
                    officeLat, officeLng,
                    currentLat, currentLng
                );

                if (distanceEl) {

                    distanceEl.textContent = Math.round(distance) + ' m';

                }

                const inside = officePolygon && officePolygon.length >= 3

                    ? isPointInPolygon(currentLat, currentLng, officePolygon)

                    : distance <= officeRadius;

                if (statusEl) {

                    statusEl.textContent = inside

                        ? 'Di dalam area'

                        : 'Di luar area';

                    statusEl.className = inside

                        ? 'rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700'

                        : 'rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-semibold text-red-700';

                }

                if (map) {

                    if (marker) {

                        marker.setLatLng([currentLat, currentLng]);

                    } else {

                        marker = L.marker(

                            [currentLat, currentLng],

                            {

                                icon: L.divIcon({

                                    className: '',

                                    html: '<div style="width:14px;height:14px;border-radius:50%;background:#22c55e;border:3px solid white;box-shadow:0 0 0 2px #22c55e;"></div>',

                                }),

                            }

                        ).addTo(map);

                    }

                }

            }

            if (checkInBtn) checkInBtn.disabled = false;
            if (checkOutBtn) checkOutBtn.disabled = false;

        }

        function locationError() {

            if (statusEl) {

                statusEl.textContent = 'GPS tidak tersedia';

                statusEl.className = 'rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-500';

            }

        }

        if (navigator.geolocation) {

            navigator.geolocation.getCurrentPosition(

                updateLocation,

                locationError,

                { enableHighAccuracy: true }

            );

            navigator.geolocation.watchPosition(

                updateLocation,

                locationError,

                { enableHighAccuracy: true }

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Check In / Check Out
        |--------------------------------------------------------------------------
        */

        async function submitAttendance(url, button) {

            if (currentLat === null || currentLng === null) {

                alert('Lokasi GPS belum tersedia. Pastikan izin lokasi diaktifkan.');

                return;

            }

            button.disabled = true;

            const originalText = button.innerHTML;

            button.innerHTML = 'Memproses...';

            window.SWMS?.showLoading();

            try {

                const response = await fetch(url, {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN': csrfToken,

                        'Accept': 'application/json',

                    },

                    body: JSON.stringify({

                        latitude: currentLat,

                        longitude: currentLng,

                    }),

                });

                const data = await response.json();

                if (!data.success) {

                    let message = data.message;

                    if (data.distance !== undefined) {

                        message += `\n\nDistance: ${Math.round(data.distance)} m\nAllowed Radius: ${data.radius} m`;

                    }

                    window.SWMS?.hideLoading();

                    alert(message);

                    button.disabled = false;

                    button.innerHTML = originalText;

                    return;

                }

                window.SWMS?.hideLoading();
                window.SWMS?.showComplete();

                setTimeout(() => window.location.reload(), 900);

            } catch (error) {

                console.error(error);

                window.SWMS?.hideLoading();

                alert('Terjadi kesalahan. Silakan coba lagi.');

                button.disabled = false;

                button.innerHTML = originalText;

            }

        }

        checkInBtn?.addEventListener('click', () => {

            submitAttendance(

                '{{ route("employee.attendance.check-in") }}',

                checkInBtn

            );

        });

        checkOutBtn?.addEventListener('click', () => {

            submitAttendance(

                '{{ route("employee.attendance.check-out") }}',

                checkOutBtn

            );

        });

    }

    if (window.lucide) {

        lucide.createIcons();

    }

});

</script>
@endpush

@endsection