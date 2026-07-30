@php
    $previewLat = $office->latitude ?? -3.319437;
    $previewLng = $office->longitude ?? 114.590752;
    $previewRadius = $office->radius ?? 200;
    $previewMapId = 'office-map-preview-' . $office->id;
@endphp

<x-ui.card>

    {{-- Map (Read Only) --}}
    <div
        id="{{ $previewMapId }}"
        class="h-[320px] overflow-hidden rounded-2xl border border-slate-300 shadow-sm">
    </div>

    {{-- Coordinate (Compact) --}}
    <div class="mt-3 flex flex-wrap items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs">

        <span class="text-slate-500">Lat</span>
        <span class="font-semibold text-slate-700">{{ $previewLat }}</span>

        <span class="mx-1 text-slate-300">|</span>

        <span class="text-slate-500">Long</span>
        <span class="font-semibold text-slate-700">{{ $previewLng }}</span>

        <span class="mx-1 text-slate-300">|</span>

        <span class="text-blue-600">Radius</span>
        <span class="font-semibold text-blue-700">{{ number_format($previewRadius) }} m</span>

    </div>

    <p class="mt-2 text-[11px] leading-snug text-slate-400">
        Read-only preview. Use "View / Edit" below to change the location, radius, or polygon area.
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