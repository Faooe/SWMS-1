@props([
    'assignment' => null,
])

<x-assignment.section-card
    title="Location Information"
    description="Select assignment location and attendance radius."
    icon="map-pin">

    <div class="grid gap-6 md:grid-cols-2">

        {{-- Location Name --}}
        <x-ui.input
            id="location_name"
            name="location_name"
            label="Location Name"
            :value="$assignment?->location_name"
            required />

        {{-- Radius --}}
        <x-ui.input
            id="radius"
            name="radius"
            type="number"
            label="Radius (Meter)"
            :value="$assignment?->radius ?? 200"
            required />

        {{-- Latitude --}}
        <x-ui.input
            id="latitude"
            name="latitude"
            label="Latitude"
            :value="$assignment?->latitude"
            readonly />

        {{-- Longitude --}}
        <x-ui.input
            id="longitude"
            name="longitude"
            label="Longitude"
            :value="$assignment?->longitude"
            readonly />

    </div>

    <input
    type="hidden"
    id="polygon"
    name="polygon"
    value="{{ old('polygon', $assignment?->polygon ? json_encode($assignment->polygon) : '') }}">

    <div class="mt-6">

        <label
            for="address"
            class="mb-2 block text-sm font-semibold text-slate-700">

            Address

        </label>

        <textarea

            id="address"

            name="address"

            rows="3"

            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"

        >{{ old('address', $assignment?->address) }}</textarea>

    </div>

    <div class="mt-8">

            <div class="mb-3 flex flex-wrap items-center justify-between gap-3">

        <label
            class="block text-sm font-semibold text-slate-700">

            Pick Location on Map

        </label>

        <div class="flex gap-2">

            <button

                type="button"

                id="btn-clear-polygon"

                class="inline-flex items-center gap-2 rounded-xl border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-100">

                <i
                    data-lucide="trash-2"
                    class="h-4 w-4">

                </i>

                Clear Polygon

            </button>

            <button

                type="button"

                id="current-location"

                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"

            >

                <i
                    data-lucide="crosshair"
                    class="h-4 w-4">

                </i>

                Use Current Location

            </button>

        </div>

    </div>

    <p
        id="polygon-status"
        class="mb-3 text-sm text-slate-500">

        Belum ada area polygon. Gunakan tools gambar poligon di pojok kiri atas peta (opsional), atau biarkan kosong untuk pakai radius bulat seperti biasa.

    </p>

    <div

        id="assignment-map"

        class="h-[500px] w-full rounded-3xl border border-slate-300"

    ></div>

    </div>

</x-assignment.section-card>

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Form Components
    |--------------------------------------------------------------------------
    */

    const latitudeInput = document.getElementById('latitude');

    const longitudeInput = document.getElementById('longitude');

    const radiusInput = document.getElementById('radius');

    const polygonInput = document.getElementById('polygon');

    const polygonStatus = document.getElementById('polygon-status');

    const clearPolygonButton = document.getElementById('btn-clear-polygon');

    const addressInput = document.getElementById('address');

    const locationInput = document.getElementById('location_name');

    const currentLocationButton = document.getElementById(
        'current-location'
    );

    /*
    |--------------------------------------------------------------------------
    | Default Coordinate
    |--------------------------------------------------------------------------
    */

    const defaultLat = parseFloat(latitudeInput.value || -3.3220785);

    const defaultLng = parseFloat(longitudeInput.value || 114.5871803);

    /*
    |--------------------------------------------------------------------------
    | Map
    |--------------------------------------------------------------------------
    */

    const map = L.map('assignment-map').setView(

        [defaultLat, defaultLng],

        15

    );

    L.tileLayer(

        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

        {

            attribution: '&copy; OpenStreetMap'

        }

    ).addTo(map);

    /*
    |--------------------------------------------------------------------------
    | Marker
    |--------------------------------------------------------------------------
    */

    const marker = L.marker(

        [defaultLat, defaultLng],

        {

            draggable: true

        }

    ).addTo(map);

    /*
    |--------------------------------------------------------------------------
    | Radius Circle
    |--------------------------------------------------------------------------
    */

    const circle = L.circle(

        [defaultLat, defaultLng],

        {

            radius: Number(radiusInput.value),

            color: '#2563eb',

            weight: 2,

            fillColor: '#3b82f6',

            fillOpacity: 0.15,

        }

    ).addTo(map);

    /*
    |--------------------------------------------------------------------------
    | Polygon Draw Layer
    |--------------------------------------------------------------------------
    */

    const drawnItems = new L.FeatureGroup();

    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({

        draw: {

            polygon: {

                allowIntersection: false,

                showArea: true,

                shapeOptions: {

                    color: '#f59e0b',

                    fillColor: '#fbbf24',

                    fillOpacity: .25,

                },

            },

            polyline: false,

            rectangle: false,

            circle: false,

            circlemarker: false,

            marker: false,

        },

        edit: {

            featureGroup: drawnItems,

            remove: true,

        },

    });

    map.addControl(drawControl);

    function updatePolygonStatus(hasPolygon) {

        if (!polygonStatus) return;

        polygonStatus.textContent = hasPolygon

            ? 'Area polygon aktif — validasi absensi memakai bentuk area ini, bukan radius bulat.'

            : 'Belum ada area polygon. Gunakan tools gambar poligon di pojok kiri atas peta (opsional), atau biarkan kosong untuk pakai radius bulat seperti biasa.';

    }

    function savePolygonFromLayer(layer) {

        const latlngs = layer.getLatLngs()[0];

        const polygon = latlngs.map((point) => [

            point.lat,

            point.lng,

        ]);

        polygonInput.value = JSON.stringify(polygon);

        updatePolygonStatus(true);

    }

    map.on(L.Draw.Event.CREATED, function (event) {

        drawnItems.clearLayers();

        drawnItems.addLayer(event.layer);

        savePolygonFromLayer(event.layer);

    });

    map.on(L.Draw.Event.EDITED, function (event) {

        event.layers.eachLayer(function (layer) {

            savePolygonFromLayer(layer);

        });

    });

    map.on(L.Draw.Event.DELETED, function () {

        polygonInput.value = '';

        updatePolygonStatus(false);

    });

    clearPolygonButton?.addEventListener('click', function () {

        drawnItems.clearLayers();

        polygonInput.value = '';

        updatePolygonStatus(false);

    });

    if (polygonInput.value) {

        try {

            const existingPolygon = JSON.parse(polygonInput.value);

            const latlngs = existingPolygon.map((point) => [

                point[0],

                point[1],

            ]);

            const layer = L.polygon(latlngs, {

                color: '#f59e0b',

                fillColor: '#fbbf24',

                fillOpacity: .25,

            });

            drawnItems.addLayer(layer);

            updatePolygonStatus(true);

        } catch (error) {

            console.error('Failed to load existing polygon:', error);

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Reverse Geocoding
    |--------------------------------------------------------------------------
    */

    async function reverseGeocode(lat, lng)
    {

        try {

            const response = await fetch(

                `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`,

                {

                    headers: {

                        'Accept': 'application/json'

                    }

                }

            );

            const data = await response.json();

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            if (data.display_name) {

                addressInput.value = data.display_name;

            }

        /*
        |--------------------------------------------------------------------------
        | Location Name
        |--------------------------------------------------------------------------
        */

        locationInput.value =
            data.name
            || data.address?.building
            || data.address?.amenity
            || data.address?.office
            || data.address?.tourism
            || data.address?.shop
            || data.address?.road
            || data.address?.suburb
            || data.address?.village
            || data.address?.city
            || 'Selected Location';

        }

        catch (error) {

            console.error(

                'Reverse Geocoding Error:',

                error

            );

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Update Coordinate
    |--------------------------------------------------------------------------
    */

    function updateLocation(lat, lng)
    {

        latitudeInput.value = lat.toFixed(7);

        longitudeInput.value = lng.toFixed(7);

        marker.setLatLng([lat, lng]);

        circle.setLatLng([lat, lng]);

        reverseGeocode(lat, lng);

    }

    /*
    |--------------------------------------------------------------------------
    | Move Map (Fly To Location)
    |--------------------------------------------------------------------------
    */

    function moveMap(lat, lng)
    {

        map.setView(

            [lat, lng],

            18,

            {

                animate: true,

                duration: 1

            }

        );

        updateLocation(

            lat,

            lng

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Click Map
    |--------------------------------------------------------------------------
    */

    map.on('click', function(e) {

        updateLocation(

            e.latlng.lat,

            e.latlng.lng

        );

    });

    /*
    |--------------------------------------------------------------------------
    | Drag Marker
    |--------------------------------------------------------------------------
    */

    marker.on('dragend', function(e) {

        const position = e.target.getLatLng();

        updateLocation(

            position.lat,

            position.lng

        );

    });

    /*
    |--------------------------------------------------------------------------
    | Radius Change
    |--------------------------------------------------------------------------
    */

    radiusInput.addEventListener('input', function() {

        circle.setRadius(

            Number(this.value)

        );

    });

    /*
    |--------------------------------------------------------------------------
    | Current Location
    |--------------------------------------------------------------------------
    */

    currentLocationButton.addEventListener(

        'click',

        function(){

            if(!navigator.geolocation){

                alert(

                    'Geolocation is not supported.'

                );

                return;

            }

            currentLocationButton.disabled = true;

            currentLocationButton.innerHTML =
                'Detecting...';

            navigator.geolocation.getCurrentPosition(

                function(position){

                    moveMap(

                        position.coords.latitude,

                        position.coords.longitude

                    );

                    currentLocationButton.disabled = false;

                    currentLocationButton.innerHTML = `
                    <i data-lucide="crosshair" class="h-4 w-4"></i>
                    Use Current Location
                    `;

                    lucide.createIcons();

                },

                function(error){

                    alert(

                        'Unable to get current location.'

                    );

                    currentLocationButton.disabled = false;

                    currentLocationButton.innerHTML = `
                    <i data-lucide="crosshair" class="h-4 w-4"></i>
                    Use Current Location
                    `;

                    lucide.createIcons();

                },

                {

                    enableHighAccuracy:true,

                    timeout:10000

                }

            );

        }

    );

    /*
    |--------------------------------------------------------------------------
    | Initial Location
    |--------------------------------------------------------------------------
    */

    if (
        latitudeInput.value &&
        longitudeInput.value
    ) {

        marker.setLatLng([
            parseFloat(latitudeInput.value),
            parseFloat(longitudeInput.value)
        ]);

        circle.setLatLng([
            parseFloat(latitudeInput.value),
            parseFloat(longitudeInput.value)
        ]);

        circle.setRadius(
            Number(radiusInput.value)
        );

        map.setView([
            parseFloat(latitudeInput.value),
            parseFloat(longitudeInput.value)
        ], 18);

        reverseGeocode(
            parseFloat(latitudeInput.value),
            parseFloat(longitudeInput.value)
        );

    } else {

        reverseGeocode(
            defaultLat,
            defaultLng
        );

    }

});

</script>

@endpush