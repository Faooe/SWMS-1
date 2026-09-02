@props(['assignment'])

<x-assignment.section-card title="Lokasi & Area Attendance" description="Lokasi kerja dan batas area verifikasi attendance." icon="map-pin">
    <div class="grid gap-5 lg:grid-cols-[minmax(240px,.75fr)_minmax(0,1.25fr)]">
        <div class="space-y-4">
            <div><p class="text-xs font-medium text-slate-400">Lokasi</p><p class="mt-1 font-semibold text-slate-800">{{ $assignment->location_name }}</p></div>
            <div><p class="text-xs font-medium text-slate-400">Alamat</p><p class="mt-1 text-sm leading-6 text-slate-600">{{ $assignment->address ?: '-' }}</p></div>
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl bg-slate-50 px-3 py-3"><p class="text-[11px] text-slate-400">Latitude</p><p class="mt-1 truncate text-xs font-semibold text-slate-700">{{ $assignment->latitude }}</p></div>
                <div class="rounded-xl bg-slate-50 px-3 py-3"><p class="text-[11px] text-slate-400">Longitude</p><p class="mt-1 truncate text-xs font-semibold text-slate-700">{{ $assignment->longitude }}</p></div>
            </div>
            <div class="flex items-center justify-between rounded-xl border border-blue-100 bg-blue-50 px-4 py-3"><span class="text-xs font-semibold text-blue-700">Radius Attendance</span><strong class="text-sm text-blue-700">{{ $assignment->radius }} m</strong></div>
            <a target="_blank" rel="noopener" href="https://maps.google.com/?q={{ $assignment->latitude }},{{ $assignment->longitude }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700"><i data-lucide="navigation" class="h-4 w-4"></i>Buka di Google Maps</a>
        </div>
        <div id="show-map" class="h-[320px] rounded-2xl border border-slate-200"></div>
    </div>
</x-assignment.section-card>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const lat={{ $assignment->latitude }};
    const lng={{ $assignment->longitude }};
    const radius={{ $assignment->radius }};
    const map=L.map('show-map').setView([lat,lng],16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap'}).addTo(map);
    L.marker([lat,lng]).addTo(map);
    L.circle([lat,lng],{radius,color:'#2563eb',fillColor:'#3b82f6',fillOpacity:.12}).addTo(map);
});
</script>
@endpush
