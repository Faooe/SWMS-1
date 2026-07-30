@props([
    'assignment'
])

<x-assignment.section-card
    title="Location Information"
    description="Assignment destination and attendance radius."
    icon="map-pin">

    <div class="grid gap-6 lg:grid-cols-2">

        <div>

            <div class="space-y-5">

                <div>

                    <p class="text-sm text-slate-500">

                        Location

                    </p>

                    <h3 class="mt-1 text-lg font-semibold">

                        {{ $assignment->location_name }}

                    </h3>

                </div>

                <div>

                    <p class="text-sm text-slate-500">

                        Address

                    </p>

                    <p class="mt-1">

                        {{ $assignment->address }}

                    </p>

                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div>

                        <p class="text-sm text-slate-500">

                            Latitude

                        </p>

                        <h4 class="font-semibold">

                            {{ $assignment->latitude }}

                        </h4>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">

                            Longitude

                        </p>

                        <h4 class="font-semibold">

                            {{ $assignment->longitude }}

                        </h4>

                    </div>

                </div>

                <div>

                    <p class="text-sm text-slate-500">

                        Attendance Radius

                    </p>

                    <h3 class="text-lg font-bold text-blue-600">

                        {{ $assignment->radius }} Meter

                    </h3>

                </div>

                <a
                    target="_blank"
                    href="https://maps.google.com/?q={{ $assignment->latitude }},{{ $assignment->longitude }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700">

                    <i data-lucide="navigation"></i>

                    Open Google Maps

                </a>

            </div>

        </div>

        <div>

            <div
                id="show-map"
                class="h-[420px] rounded-3xl border border-slate-300">

            </div>

        </div>

    </div>

</x-assignment.section-card>

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded',()=>{

    const lat={{ $assignment->latitude }};

    const lng={{ $assignment->longitude }};

    const radius={{ $assignment->radius }};

    const map=L.map('show-map').setView([lat,lng],16);

    L.tileLayer(

        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

        {

            attribution:'© OpenStreetMap'

        }

    ).addTo(map);

    L.marker([lat,lng]).addTo(map);

    L.circle([lat,lng],{

        radius:radius,

        color:'#2563eb',

        fillColor:'#3b82f6',

        fillOpacity:.15

    }).addTo(map);

});

</script>

@endpush