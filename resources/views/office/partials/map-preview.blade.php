@php
    $previewLat = $office->latitude ?? -3.319437;
    $previewLng = $office->longitude ?? 114.590752;
    $previewRadius = $office->radius ?? 200;
    $previewMapId = 'office-map-preview-' . $office->id;
@endphp

<x-ui.card>

    {{-- Header --}}
    <div class="flex items-start justify-between">

        <div class="flex items-start gap-4">

            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100">
                <i data-lucide="map-pinned" class="h-7 w-7 text-blue-600"></i>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-slate-800">Office Location</h2>
                <p class="mt-1 text-slate-500">
                    Attendance point and radius currently configured for this office.
                </p>
            </div>

        </div>

        <span class="rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
            GPS Ready
        </span>

    </div>

    {{-- Map (Read Only) --}}
    <div
        id="{{ $previewMapId }}"
        class="mt-8 h-[500px] overflow-hidden rounded-3xl border border-slate-300 shadow-lg">
    </div>

    {{-- Coordinate --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-3">

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-xs text-slate-500">Latitude</p>
            <h3 class="mt-1 text-sm font-bold text-slate-800">{{ $previewLat }}</h3>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-xs text-slate-500">Longitude</p>
            <h3 class="mt-1 text-sm font-bold text-slate-800">{{ $previewLng }}</h3>
        </div>

        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
            <p class="text-xs text-blue-600">Attendance Radius</p>
            <h3 class="mt-1 text-sm font-bold text-blue-700">{{ number_format($previewRadius) }} Meter</h3>
        </div>

    </div>

    <p class="mt-4 text-xs text-slate-400">
        This is a read-only preview. Use the "View / Edit" action below to change the location, radius, or polygon area.
    </p>

</x-ui.card>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const mapElement = document.getElementById('{{ $previewMapId }}');

    if (!mapElement || typeof L === 'undefined') {
        return;
    }

    const lat = {{ $previewLat }};
    const lng = {{ $previewLng }};
    const radius = {{ $previewRadius }};

    const map = L.map('{{ $previewMapId }}', {
        zoomControl: true,
        scrollWheelZoom: false,
    }).setView([lat, lng], 16);

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
        .bindPopup('<b>{{ addslashes($office->name) }}</b><br>Attendance Point')
        .openPopup();

    L.circle([lat, lng], {
        radius: radius,
        color: '#2563eb',
        fillColor: '#3b82f6',
        fillOpacity: .18,
        weight: 2,
    }).addTo(map);

    @if($office->polygon)
        try {
            const polygonPoints = @json($office->polygon);

            L.polygon(polygonPoints, {
                color: '#f59e0b',
                fillColor: '#fbbf24',
                fillOpacity: .2,
            }).addTo(map);
        } catch (error) {
            console.error('Failed to render polygon preview:', error);
        }
    @endif

    if (window.lucide) {
        lucide.createIcons();
    }

});
</script>
@endpush