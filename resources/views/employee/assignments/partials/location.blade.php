<div
    class="rounded-3xl bg-white p-8 shadow">

    <div class="mb-6 flex items-center gap-3">

        <div
            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100">

            <i
                data-lucide="map-pin"
                class="h-6 w-6 text-green-600">
            </i>

        </div>

        <div>

            <h2
                class="text-xl font-bold">

                Assignment Location

            </h2>

            <p
                class="text-sm text-slate-500">

                Navigate to this destination.

            </p>

        </div>

    </div>

    {{-- Map --}}
    <div
        id="assignment-map"
        class="mb-6 h-[420px] overflow-hidden rounded-2xl border">
    </div>

    {{-- Information --}}
    <div
        class="grid gap-6 md:grid-cols-2">

        <div>

            <p
                class="text-sm text-slate-500">

                Location

            </p>

            <h3
                class="mt-2 font-semibold">

                {{ $assignment->location_name }}

            </h3>

            <p
                class="mt-2 text-sm text-slate-500">

                {{ $assignment->address }}

            </p>

        </div>

        <div>

            <p
                class="text-sm text-slate-500">

                Allowed Radius

            </p>

            <h3
                class="mt-2 text-2xl font-bold text-blue-600">

                {{ $assignment->radius }} m

            </h3>

        </div>

    </div>

</div>

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded',()=>{

    const map=L.map('assignment-map').setView(

        [

            {{ $assignment->latitude }},

            {{ $assignment->longitude }}

        ],

        17

    );

    L.tileLayer(

        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

        {

            attribution:'© OpenStreetMap'

        }

    ).addTo(map);

    const point=[

        {{ $assignment->latitude }},

        {{ $assignment->longitude }}

    ];

    L.marker(point)

        .addTo(map)

        .bindPopup(

            "{{ $assignment->title }}"

        )

        .openPopup();

    L.circle(

        point,

        {

            radius:{{ $assignment->radius }},

            color:'#2563eb',

            fillColor:'#3b82f6',

            fillOpacity:0.20

        }

    ).addTo(map);

});

</script>

@endpush