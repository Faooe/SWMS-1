@php($hasPolygon = is_array($assignment->polygon) && count($assignment->polygon) >= 3)
<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600"><i data-lucide="map-pinned" class="h-5 w-5"></i></div>
            <div>
                <h2 class="font-bold text-slate-900">Lokasi Assignment</h2>
                <p class="text-xs text-slate-500">{{ $hasPolygon ? 'Check In/Out harus berada di dalam area polygon.' : 'Check In/Out harus berada dalam radius lokasi yang diizinkan.' }}</p>
            </div>
        </div>
        <a target="_blank" rel="noopener" href="https://www.google.com/maps?q={{ $assignment->latitude }},{{ $assignment->longitude }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100"><i data-lucide="navigation" class="h-3.5 w-3.5"></i>Buka Maps</a>
    </div>

    <div id="assignment-map" class="h-[300px] overflow-hidden rounded-2xl border border-slate-200 sm:h-[380px]"></div>

    <div class="mt-5 grid gap-3 sm:grid-cols-[minmax(0,1fr)_180px]">
        <div class="rounded-2xl bg-slate-50 p-4">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Lokasi</p>
            <p class="mt-1 font-bold text-slate-800">{{ $assignment->location_name ?: '-' }}</p>
            <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $assignment->address ?: '-' }}</p>
        </div>
        <div class="rounded-2xl {{ $hasPolygon ? 'bg-amber-50' : 'bg-blue-50' }} p-4">
            <p class="text-xs font-bold uppercase tracking-wide {{ $hasPolygon ? 'text-amber-500' : 'text-blue-500' }}">Metode</p>
            <p class="mt-1 text-lg font-black {{ $hasPolygon ? 'text-amber-700' : 'text-blue-700' }}">{{ $hasPolygon ? 'Area Polygon' : ($assignment->radius . ' m') }}</p>
            <p class="mt-1 text-xs {{ $hasPolygon ? 'text-amber-600' : 'text-blue-600' }}">Area valid Check In/Out</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const mapElement = document.getElementById('assignment-map');
    if (!mapElement || typeof L === 'undefined') return;
    const point = [@json((float) $assignment->latitude), @json((float) $assignment->longitude)];
    const polygon = @json($hasPolygon ? $assignment->polygon : []);
    const radius = @json($hasPolygon ? null : $assignment->radius);
    const map = L.map(mapElement).setView(point, 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
    L.marker(point).addTo(map).bindPopup(@json($assignment->title));
    if (polygon.length >= 3) {
        const layer = L.polygon(polygon, { color:'#d97706', fillColor:'#fbbf24', fillOpacity:.18, weight:2 }).addTo(map);
        map.fitBounds(layer.getBounds(), {padding:[20,20]});
    } else if (radius) {
        L.circle(point, { radius, color:'#2563eb', fillColor:'#3b82f6', fillOpacity:.16, weight:2 }).addTo(map);
    }
});
</script>
@endpush
