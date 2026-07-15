<x-ui.card>

    {{-- Header --}}
    <div class="flex items-start justify-between">

        <div class="flex items-start gap-4">

            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100">

                <i
                    data-lucide="map-pinned"
                    class="h-7 w-7 text-blue-600">
                </i>

            </div>

            <div>

                <h2 class="text-2xl font-bold text-slate-800">

                    Office Location

                </h2>

                <p class="mt-1 text-slate-500">

                    Select the office coordinate that will be used for attendance validation.

                </p>

            </div>

        </div>

        <span
            class="rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">

            GPS Ready

        </span>

    </div>

    {{-- Search --}}
    <div class="mt-8">

        <label class="mb-3 block text-sm font-semibold text-slate-700">

            Search Address

        </label>

        <div class="relative">

            <i
                data-lucide="search"
                class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400">
            </i>

            <input

                id="search-address"

                type="text"

                placeholder="Search office address..."

                class="w-full rounded-2xl border border-slate-300 py-4 pl-12 pr-4 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

        </div>

        <p
            id="search-status"
            class="mt-2 text-sm text-slate-500">

            Start typing to search location automatically.

        </p>

    </div>

   {{-- Action --}}
    <div class="mt-6 flex flex-wrap gap-3">

        <button

            type="button"

            id="btn-current-location"

            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 font-semibold text-white transition hover:bg-emerald-700">

            <i
                data-lucide="locate-fixed"
                class="h-5 w-5">
            </i>

            Use My Current Location

        </button>

        <button

            type="button"

            id="btn-reset"

            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 font-semibold transition hover:bg-slate-100">

            <i
                data-lucide="rotate-ccw"
                class="h-5 w-5">
            </i>

            Reset Location

        </button>

        <button

            type="button"

            id="btn-clear-polygon"

            class="inline-flex items-center gap-2 rounded-xl border border-amber-300 bg-amber-50 px-5 py-3 font-semibold text-amber-700 transition hover:bg-amber-100">

            <i
                data-lucide="trash-2"
                class="h-5 w-5">
            </i>

            Clear Polygon Area

        </button>

        <button

            type="button"

            id="btn-open-map"

            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 font-semibold transition hover:bg-slate-100">

            <i
                data-lucide="external-link"
                class="h-5 w-5">
            </i>

            Open Google Maps

        </button>

    </div>

    <p
        id="polygon-status"
        class="mt-3 text-sm text-slate-500">

        Belum ada area polygon. Gunakan tools gambar poligon di pojok kiri atas peta (opsional), atau biarkan kosong untuk pakai radius bulat seperti biasa.

    </p>

    {{-- MAP --}}
    <div
        id="office-map"
        class="mt-8 h-[600px] overflow-hidden rounded-3xl border border-slate-300 shadow-lg">

    </div>

    <input
        type="hidden"
        id="polygon"
        name="polygon"
        value="{{ old('polygon', $office->polygon ? json_encode($office->polygon) : '') }}">

    {{-- Coordinate --}}
    <div class="mt-8 grid gap-6 lg:grid-cols-3">

        <div
            class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

            <p class="text-sm text-slate-500">

                Latitude

            </p>

            <h3

                id="latitude-text"

                class="mt-2 text-xl font-bold text-slate-800">

                {{ old('latitude',$office->latitude ?? '-3.319437') }}

            </h3>

            <input

                hidden

                id="latitude"

                name="latitude"

                value="{{ old('latitude',$office->latitude ?? '-3.319437') }}">

        </div>

        <div
            class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

            <p class="text-sm text-slate-500">

                Longitude

            </p>

            <h3

                id="longitude-text"

                class="mt-2 text-xl font-bold text-slate-800">

                {{ old('longitude',$office->longitude ?? '114.590752') }}

            </h3>

            <input

                hidden

                id="longitude"

                name="longitude"

                value="{{ old('longitude',$office->longitude ?? '114.590752') }}">

        </div>

        <div
            class="rounded-2xl border border-blue-200 bg-blue-50 p-6">

            <p class="text-sm text-blue-600">

                Attendance Radius

            </p>

            <h3

                id="radius-text"

                class="mt-2 text-2xl font-bold text-blue-700">

                {{ old('radius',$office->radius ?? 200) }} Meter

            </h3>

        </div>

    </div>

    {{-- Radius --}}
    <div class="mt-8">

        <div class="mb-3 flex items-center justify-between">

            <span class="font-semibold">

                Attendance Radius

            </span>

            <span
                id="radius-value"
                class="text-blue-600 font-bold">

                {{ old('radius',$office->radius ?? 200) }} m

            </span>

        </div>

        <input

            id="radius-slider"

            type="range"

            min="50"

            max="1000"

            step="10"

            value="{{ old('radius',$office->radius ?? 200) }}"

            class="h-2 w-full cursor-pointer rounded-lg bg-slate-200">

        <input
            hidden
            id="radius"
            name="radius"
            value="{{ old('radius',$office->radius ?? 200) }}">

    </div>

    {{-- Enterprise Info Cards --}}
    <div class="mt-8 grid gap-6 lg:grid-cols-4">

        <div class="rounded-2xl border bg-slate-50 p-5">

            <p class="text-sm text-slate-500">
                Coverage Area
            </p>

            <h3
                id="coverage-area"
                class="mt-2 text-xl font-bold text-slate-800">

                -
            </h3>

        </div>

        <div class="rounded-2xl border bg-slate-50 p-5">

            <p class="text-sm text-slate-500">
                GPS Accuracy
            </p>

            <h3
                id="gps-accuracy"
                class="mt-2 text-xl font-bold text-green-600">

                -
            </h3>

        </div>

        <div class="rounded-2xl border bg-slate-50 p-5">

            <p class="text-sm text-slate-500">
                Office Status
            </p>

            <h3 class="mt-2 text-xl font-bold text-blue-600">

                Ready

            </h3>

        </div>

        <div class="rounded-2xl border bg-slate-50 p-5">

            <p class="text-sm text-slate-500">

                Coordinate

            </p>

            <button

                id="copy-coordinate"

                type="button"

                class="mt-2 rounded-lg bg-slate-900 px-4 py-2 text-white transition hover:bg-slate-800">

                Copy

            </button>

        </div>

    </div>

    {{-- Info --}}
    <div
        class="mt-8 rounded-2xl border border-blue-200 bg-blue-50 p-5">

        <div class="flex gap-3">

            <i
                data-lucide="info"
                class="mt-1 h-5 w-5 text-blue-600">

            </i>

            <div>

                <h4 class="font-semibold text-blue-700">

                    Information

                </h4>

                <p class="mt-1 text-sm text-blue-600">

                    Employees can only check in within the office radius.
                    Drag the marker or click the map to adjust the office position.

                </p>

            </div>

        </div>

    </div>

</x-ui.card>

@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Element
    |--------------------------------------------------------------------------
    */

    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    const polygonInput = document.getElementById('polygon');
    const polygonStatus = document.getElementById('polygon-status');
    const clearPolygonButton = document.getElementById('btn-clear-polygon');

    const latitudeText = document.getElementById('latitude-text');
    const longitudeText = document.getElementById('longitude-text');

    const searchInput = document.getElementById('search-address');
    const searchStatus = document.getElementById('search-status');

    const radiusSlider = document.getElementById('radius-slider');
    const radiusValue = document.getElementById('radius-value');
    const radiusText = document.getElementById('radius-text');
    const radiusInput = document.getElementById('radius');

    const currentButton = document.getElementById('btn-current-location');
    const googleButton = document.getElementById('btn-open-map');
    const resetButton = document.getElementById('btn-reset');
    const copyButton = document.getElementById('copy-coordinate');

    const coverageArea = document.getElementById('coverage-area');
    const gpsAccuracy = document.getElementById('gps-accuracy');

    /*
    |--------------------------------------------------------------------------
    | Initial Coordinate
    |--------------------------------------------------------------------------
    */

    let latitude = parseFloat(latitudeInput.value);
    let longitude = parseFloat(longitudeInput.value);
    let radius = parseInt(radiusSlider.value);

    const defaultLat = latitude;
    const defaultLng = longitude;

    /*
    |--------------------------------------------------------------------------
    | Leaflet Map
    |--------------------------------------------------------------------------
    */

    const map = L.map('office-map', {

        zoomControl: true,

    }).setView([latitude, longitude], 16);

    /*
    |--------------------------------------------------------------------------
    | Layers (Street / Satellite)
    |--------------------------------------------------------------------------
    */

    const street = L.tileLayer(

        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

        {

            maxZoom: 19,

            attribution: '&copy; OpenStreetMap',

        }

    );

    const satellite = L.tileLayer(

        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',

        {

            maxZoom: 19,

            attribution: 'Esri',

        }

    );

    street.addTo(map);

    L.control.layers(

        {

            Street: street,

            Satellite: satellite,

        }

    ).addTo(map);

    /*
    |--------------------------------------------------------------------------
    | Scale Control
    |--------------------------------------------------------------------------
    */

    L.control.scale({

        metric: true,

        imperial: false,

    }).addTo(map);

    /*
    |--------------------------------------------------------------------------
    | Marker
    |--------------------------------------------------------------------------
    */

    const marker = L.marker(

        [latitude, longitude],

        {

            draggable: true,

        }

    ).addTo(map);

    marker.bindPopup(

        '<b>Office Location</b><br>Attendance Point'

    ).openPopup();

    /*
    /*
    |--------------------------------------------------------------------------
    | Circle
    |--------------------------------------------------------------------------
    */

    const circle = L.circle(

        [latitude, longitude],

        {

            radius: radius,

            color: '#2563eb',

            fillColor: '#3b82f6',

            fillOpacity: .18,

            weight: 2,

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

    /*
    |--------------------------------------------------------------------------
    | Load Existing Polygon
    |--------------------------------------------------------------------------
    */

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
    | Coverage Area
    |--------------------------------------------------------------------------
    */

    function updateCoverage(radius)
    {

        const area = Math.PI * radius * radius;

        coverageArea.innerHTML = (area / 1000000).toFixed(2) + ' km²';

    }

    updateCoverage(radius);

    /*
    |--------------------------------------------------------------------------
    | Update UI
    |--------------------------------------------------------------------------
    */

    async function updateLocation(lat, lng)
    {

        latitude = lat;
        longitude = lng;

        latitudeInput.value = lat.toFixed(7);
        longitudeInput.value = lng.toFixed(7);

        latitudeText.innerHTML = lat.toFixed(7);
        longitudeText.innerHTML = lng.toFixed(7);

        marker.setLatLng([lat, lng]);

        circle.setLatLng([lat, lng]);

        map.flyTo(

            [lat, lng],

            map.getZoom(),

            {

                duration: 1.2,

            }

        );

        await reverseGeocode(lat, lng);

    }

    /*
    |--------------------------------------------------------------------------
    | Reverse Geocoding
    |--------------------------------------------------------------------------
    */

    async function reverseGeocode(lat, lng)
    {

        try{

            const response = await fetch(

                `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`

            );

            const data = await response.json();

            if(!data.address){

                return;

            }

            const address = data.address;

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            const addressField = document.getElementById('address');

            if(addressField){

                addressField.value = data.display_name ?? '';

            }

            /*
            |--------------------------------------------------------------------------
            | Province
            |--------------------------------------------------------------------------
            */

            const provinceField = document.getElementById('province');

            if(provinceField){

                provinceField.value =

                    address.state ??

                    address.province ??

                    '';

            }

            /*
            |--------------------------------------------------------------------------
            | City
            |--------------------------------------------------------------------------
            */

            const cityField = document.getElementById('city');

            if(cityField){

                cityField.value =

                    address.city ??

                    address.town ??

                    address.county ??

                    address.municipality ??

                    '';

            }

            /*
            |--------------------------------------------------------------------------
            | Postal
            |--------------------------------------------------------------------------
            */

            const postalField = document.getElementById('postal_code');

            if(postalField){

                postalField.value =

                    address.postcode ??

                    '';

            }

        }

        catch(error){

            console.log(error);

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Click Map
    |--------------------------------------------------------------------------
    */

    map.on('click', function(e){

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

    marker.on('dragend', function(e){

        const position = e.target.getLatLng();

        updateLocation(

            position.lat,

            position.lng

        );

    });

    /*
    |--------------------------------------------------------------------------
    | Radius Slider
    |--------------------------------------------------------------------------
    */

    radiusSlider.addEventListener('input', function(){

        radius = parseInt(this.value);

        circle.setRadius(radius);

        radiusValue.innerHTML = radius + ' m';

        radiusText.innerHTML = radius + ' Meter';

        if(radiusInput){

            radiusInput.value = radius;

        }

        updateCoverage(radius);

    });

    /*
    |--------------------------------------------------------------------------
    | Search Address
    |--------------------------------------------------------------------------
    */

    let timeout = null;

    searchInput.addEventListener('keyup', function(){

        clearTimeout(timeout);

        timeout = setTimeout(searchLocation, 700);

    });

    async function searchLocation()
    {

        const keyword = searchInput.value.trim();

        if(keyword === ''){

            searchStatus.innerHTML = 'Start typing to search location automatically.';

            return;

        }

        searchStatus.innerHTML = 'Searching...';

        try{

            const response = await fetch(

                'https://nominatim.openstreetmap.org/search?format=json&q=' +

                encodeURIComponent(keyword)

            );

            const data = await response.json();

            if(data.length > 0){

                const lat = parseFloat(data[0].lat);

                const lng = parseFloat(data[0].lon);

                updateLocation(lat, lng);

                map.setView([lat, lng], 17);

                searchStatus.innerHTML =

                    'Location found ✔';

            }else{

                searchStatus.innerHTML =

                    'Location not found';

            }

        }catch(error){

            console.error(error);

            searchStatus.innerHTML =

                'Search failed';

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Current Location
    |--------------------------------------------------------------------------
    */

    currentButton.addEventListener('click', function(){

        if(!navigator.geolocation){

            alert('Browser does not support GPS.');

            return;

        }

        currentButton.disabled = true;

        currentButton.innerHTML = 'Loading GPS...';

        navigator.geolocation.getCurrentPosition(

            function(position){

                updateLocation(

                    position.coords.latitude,

                    position.coords.longitude

                );

                map.setView(

                    [

                        position.coords.latitude,

                        position.coords.longitude

                    ],

                    18

                );

                gpsAccuracy.innerHTML =

                    '±' + Math.round(position.coords.accuracy) + ' m';

                currentButton.disabled = false;

                currentButton.innerHTML =

                    'Use My Current Location';

            },

            function(){

                alert('Unable to retrieve your location.');

                currentButton.disabled = false;

                currentButton.innerHTML =

                    'Use My Current Location';

            }

        );

    });

    /*
    |--------------------------------------------------------------------------
    | Reset Location
    |--------------------------------------------------------------------------
    */

    resetButton.addEventListener('click', function(){

        updateLocation(defaultLat, defaultLng);

        map.setView([defaultLat, defaultLng], 16);

    });

    /*
    |--------------------------------------------------------------------------
    | Copy Coordinate
    |--------------------------------------------------------------------------
    */

    copyButton.addEventListener('click', function(){

        navigator.clipboard.writeText(latitude + ',' + longitude);

        this.innerHTML = 'Copied';

        setTimeout(() => {

            this.innerHTML = 'Copy';

        }, 2000);

    });

    /*
    |--------------------------------------------------------------------------
    | Google Maps
    |--------------------------------------------------------------------------
    */

    googleButton.addEventListener('click', function(){

        window.open(

            'https://maps.google.com/?q=' +

            latitude +

            ',' +

            longitude,

            '_blank'

        );

    });

    /*
    |--------------------------------------------------------------------------
    | Refresh Lucide
    |--------------------------------------------------------------------------
    */

    if(window.lucide){

        lucide.createIcons();

    }

});

</script>
@endpush