@php
    $isEdit = isset($company) && ($company->exists ?? false);
@endphp

<style>
.platform-company-form input:not([type="hidden"]):not([type="checkbox"]):not([type="radio"]),
.platform-company-form select,
.platform-company-form textarea {
    border-color:#e2e8f0!important; background:#f8fafc!important; box-shadow:none!important; border-radius:14px!important;
}
.platform-company-form input:not([type="hidden"]):focus,
.platform-company-form select:focus,
.platform-company-form textarea:focus { background:#fff!important; border-color:#60a5fa!important; box-shadow:0 0 0 4px #eff6ff!important; }
.platform-company-form input[type="file"] { padding:8px 10px!important; }
</style>

<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="building-2" class="h-5 w-5"></i></span>
        <div><h2 class="text-base font-black text-slate-900">Informasi Company</h2><p class="mt-0.5 text-xs text-slate-500">Identitas dan kontak yang digunakan pada platform.</p></div>
    </div>
    <div class="p-5 sm:p-6">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <x-ui.input label="Kode Company" name="code" :value="$company->code ?? ''" placeholder="Contoh: ABC" required />
            <x-ui.input label="Nama Company" name="name" :value="$company->name ?? ''" placeholder="Nama perusahaan" required />
            <x-ui.input label="Email Company" name="email" type="email" :value="$company->email ?? ''" placeholder="company@email.com" />
            <x-ui.input label="Telepon" name="phone" :value="$company->phone ?? ''" placeholder="+62xxxxxxxx" />
            <x-ui.input label="Situs Web" name="website" :value="$company->website ?? ''" placeholder="https://company.com" />
            <div>
                <x-ui.file label="Logo Company" name="logo" data-compress-image accept=".jpg,.jpeg,.png,.webp" />
                <p class="mt-1.5 text-xs text-slate-400">JPG, PNG, atau WEBP · maks. 1MB.</p>
            </div>
        </div>
    </div>
</section>

<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div class="flex items-center gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><i data-lucide="map-pin" class="h-5 w-5"></i></span><div><h2 class="text-base font-black text-slate-900">Head Office & Area Attendance</h2><p class="mt-0.5 text-xs text-slate-500">Cari lokasi, tentukan titik kantor, lalu gambar area bila diperlukan.</p></div></div>
        <span class="w-fit rounded-full border border-emerald-100 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">GPS aktif</span>
    </div>
    <div class="p-5 sm:p-6">
        <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_auto] xl:items-end">
            <div><label for="search-address" class="mb-2 block text-sm font-semibold text-slate-700">Cari Lokasi</label><div class="relative"><i data-lucide="search" class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i><input id="search-address" type="text" placeholder="Cari alamat atau nama tempat..." class="w-full py-3 pl-11 pr-4 text-sm"></div><p id="search-status" class="mt-1.5 text-xs text-slate-400">Mulai ketik untuk mencari lokasi secara otomatis.</p></div>
            <div class="flex flex-wrap gap-2"><button type="button" id="btn-current-location" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-700"><i data-lucide="locate-fixed" class="h-4 w-4"></i>Lokasi Saya</button><button type="button" id="btn-reset" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50"><i data-lucide="rotate-ccw" class="h-4 w-4"></i>Reset</button><button type="button" id="btn-clear-polygon" class="inline-flex items-center gap-2 rounded-xl border border-amber-100 bg-amber-50 px-4 py-2.5 text-sm font-bold text-amber-700 hover:bg-amber-100"><i data-lucide="eraser" class="h-4 w-4"></i>Hapus Area</button></div>
        </div>
        <div id="company-map" class="mt-4 h-72 w-full overflow-hidden rounded-2xl border border-slate-200 bg-slate-50"></div>
        <div class="mt-3 flex items-start gap-2 rounded-xl bg-slate-50 px-3 py-2.5"><i data-lucide="info" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"></i><p id="polygon-status" class="text-xs leading-5 text-slate-500">Area polygon bersifat opsional. Tanpa polygon, sistem memakai radius Head Office.</p></div>
        <input hidden id="latitude" name="latitude" value="{{ old('latitude', $company->latitude ?? '-3.319437') }}"><input hidden id="longitude" name="longitude" value="{{ old('longitude', $company->longitude ?? '114.590752') }}"><input hidden id="polygon" name="polygon" value="{{ old('polygon', isset($company) && $company->headOffice?->polygon ? json_encode($company->headOffice->polygon) : '') }}">
    </div>
</section>

<div class="grid gap-5 xl:grid-cols-2">
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4 sm:px-6"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600"><i data-lucide="map" class="h-5 w-5"></i></span><div><h2 class="text-base font-black text-slate-900">Alamat Company</h2><p class="mt-0.5 text-xs text-slate-500">Alamat administratif tenant.</p></div></div>
        <div class="space-y-5 p-5 sm:p-6"><x-ui.textarea label="Alamat" name="address" id="address" rows="3" :value="$company->address ?? ''" placeholder="Alamat perusahaan" /><div class="grid gap-4 sm:grid-cols-3"><x-ui.input label="Provinsi" name="province" id="province" :value="$company->province ?? ''" /><x-ui.input label="Kota" name="city" id="city" :value="$company->city ?? ''" /><x-ui.input label="Kode Pos" name="postal_code" id="postal_code" :value="$company->postal_code ?? ''" /></div></div>
    </section>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4 sm:px-6"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i data-lucide="shield-check" class="h-5 w-5"></i></span><div><h2 class="text-base font-black text-slate-900">Super Administrator</h2><p class="mt-0.5 text-xs text-slate-500">Akun utama pengelola company.</p></div></div>
        <div class="grid gap-4 p-5 sm:grid-cols-2 sm:p-6"><x-ui.input label="Nama Lengkap" name="admin_name" :value="$company->admin_name ?? ''" placeholder="Nama lengkap" :required="!($company->exists ?? false)" /><x-ui.input label="Username Internal" name="admin_username" :value="$superAdmin->username ?? ($company->admin_username ?? '')" placeholder="Username" :required="!($company->exists ?? false)" /><x-ui.input label="Email Login" name="admin_email" type="email" :value="$superAdmin->email ?? ($company->admin_email ?? '')" placeholder="admin@email.com" :required="!($company->exists ?? false)" /><x-ui.input label="Telepon Admin" name="admin_phone" :value="$company->admin_phone ?? ''" placeholder="+62xxxxxxxx" /></div>
    </section>
</div>

@unless($isEdit)
<div class="flex items-start gap-3 rounded-2xl border border-blue-100 bg-blue-50/70 px-4 py-3"><div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-blue-600 ring-1 ring-blue-100"><i data-lucide="sparkles" class="h-4 w-4"></i></div><div><p class="text-sm font-bold text-slate-800">Disiapkan otomatis setelah dibuat</p><p class="mt-1 text-xs leading-5 text-slate-600">Password Super Admin, Head Office, paket Free, batas awal 50 employee, dan radius attendance 200 meter akan disiapkan oleh sistem.</p></div></div>
@endunless

<div class="flex flex-col-reverse gap-2 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm sm:flex-row sm:items-center sm:justify-end">
    <a href="{{ $isEdit ? route('platform.companies.show', $company) : route('platform.companies.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-center text-sm font-bold text-slate-600 transition hover:bg-slate-50">Batal</a>
    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100"><i data-lucide="{{ $isEdit ? 'save' : 'plus' }}" class="h-4 w-4"></i>{{ $isEdit ? 'Simpan Perubahan' : 'Buat Company' }}</button>
</div>

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

    const searchInput = document.getElementById('search-address');
    const searchStatus = document.getElementById('search-status');

    const currentButton = document.getElementById('btn-current-location');
    const resetButton = document.getElementById('btn-reset');

    /*
    |--------------------------------------------------------------------------
    | Initial Coordinate
    |--------------------------------------------------------------------------
    */

    let latitude = parseFloat(latitudeInput.value);
    let longitude = parseFloat(longitudeInput.value);

    const defaultLat = latitude;
    const defaultLng = longitude;

    /*
    |--------------------------------------------------------------------------
    | Leaflet Map
    |--------------------------------------------------------------------------
    */

    const map = L.map('company-map', {

        zoomControl: true,

    }).setView([latitude, longitude], 15);

    L.tileLayer(

        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

        {

            maxZoom: 19,

            attribution: '&copy; OpenStreetMap',

        }

    ).addTo(map);

    /*
    |--------------------------------------------------------------------------
    | Resize Handler (fix peta blur/kepotong pas resize/mode HP)
    |--------------------------------------------------------------------------
    */

    window.addEventListener('resize', () => {

        map.invalidateSize();

    });

    /*
    |--------------------------------------------------------------------------
    | Polygon Draw Layer
    |--------------------------------------------------------------------------
    */

    const polygonInput = document.getElementById('polygon');

    const polygonStatus = document.getElementById('polygon-status');

    const clearPolygonButton = document.getElementById('btn-clear-polygon');

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

            ? 'Area polygon aktif — validasi absensi Head Office memakai bentuk area ini, bukan radius bulat.'

            : 'Belum ada area polygon. Gunakan tools gambar poligon di pojok kiri atas peta (opsional), atau biarkan kosong untuk pakai radius bulat seperti biasa.';

    }

    function savePolygonFromLayer(layer) {

        const latlngs = layer.getLatLngs()[0];

        const polygon = latlngs.map((point) => [point.lat, point.lng]);

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

            const latlngs = existingPolygon.map((point) => [point[0], point[1]]);

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
    | Marker
    |--------------------------------------------------------------------------
    */

    const marker = L.marker(

        [latitude, longitude],

        {

            draggable: true,

        }

    ).addTo(map);

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

        marker.setLatLng([lat, lng]);

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

            const addressField = document.getElementById('address');

            if(addressField){

                addressField.value = data.display_name ?? '';

            }

            const provinceField = document.getElementById('province');

            if(provinceField){

                provinceField.value =

                    address.state ??

                    address.province ??

                    '';

            }

            const cityField = document.getElementById('city');

            if(cityField){

                cityField.value =

                    address.city ??

                    address.town ??

                    address.county ??

                    address.municipality ??

                    '';

            }

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

            searchStatus.innerHTML = 'Mulai ketik untuk mencari lokasi secara otomatis.';

            return;

        }

        searchStatus.innerHTML = 'Mencari...';

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

                    'Lokasi ditemukan ✔';

            }else{

                searchStatus.innerHTML =

                    'Lokasi tidak ditemukan';

            }

        }catch(error){

            console.error(error);

            searchStatus.innerHTML =

                'Pencarian gagal';

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Current Location
    |--------------------------------------------------------------------------
    */

    currentButton.addEventListener('click', function(){

        if(!navigator.geolocation){

            alert('Browser tidak mendukung GPS.');

            return;

        }

        currentButton.disabled = true;

        currentButton.innerHTML = 'Memuat GPS...';

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

                currentButton.disabled = false;

                currentButton.innerHTML =

                    'Gunakan Lokasi Saya';

            },

            function(){

                alert('Tidak dapat mengambil lokasi kamu.');

                currentButton.disabled = false;

                currentButton.innerHTML =

                    'Gunakan Lokasi Saya';

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

        map.setView([defaultLat, defaultLng], 15);

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