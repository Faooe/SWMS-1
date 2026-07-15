<?php $__env->startSection('title', 'Attendance'); ?>

<?php $__env->startSection('page-title', 'Attendance'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-6">

    <?php echo $__env->make('employee.attendance.partials.today-status', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="grid gap-6 lg:grid-cols-2">

        <?php echo $__env->make('employee.attendance.partials.office-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->make('employee.attendance.partials.assignment-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>

    
    
    

    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

        <h3 class="mb-6 text-lg font-bold text-slate-800">

            This Month

        </h3>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">

            <div class="rounded-2xl bg-green-50 p-4 text-center">

                <p class="text-2xl font-bold text-green-600">

                    <?php echo e($summary['present']); ?>


                </p>

                <p class="mt-1 text-xs font-semibold text-green-700">

                    Present

                </p>

            </div>

            <div class="rounded-2xl bg-amber-50 p-4 text-center">

                <p class="text-2xl font-bold text-amber-600">

                    <?php echo e($summary['late']); ?>


                </p>

                <p class="mt-1 text-xs font-semibold text-amber-700">

                    Late

                </p>

            </div>

            <div class="rounded-2xl bg-blue-50 p-4 text-center">

                <p class="text-2xl font-bold text-blue-600">

                    <?php echo e($summary['leave']); ?>


                </p>

                <p class="mt-1 text-xs font-semibold text-blue-700">

                    Leave

                </p>

            </div>

            <div class="rounded-2xl bg-purple-50 p-4 text-center">

                <p class="text-2xl font-bold text-purple-600">

                    <?php echo e($summary['permission']); ?>


                </p>

                <p class="mt-1 text-xs font-semibold text-purple-700">

                    Permission

                </p>

            </div>

            <div class="rounded-2xl bg-red-50 p-4 text-center">

                <p class="text-2xl font-bold text-red-600">

                    <?php echo e($summary['absent']); ?>


                </p>

                <p class="mt-1 text-xs font-semibold text-red-700">

                    Absent

                </p>

            </div>

        </div>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>

    <div class="flex justify-end">

        <a href="<?php echo e(route('employee.attendance.history')); ?>">

            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary']); ?>

                <i data-lucide="history" class="h-5 w-5"></i>

                View History

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>

        </a>

    </div>

</div>

<?php $__env->startPush('scripts'); ?>
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

                        ? 'Inside Radius'

                        : 'Outside Radius';

                    statusEl.className = inside

                        ? 'rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700'

                        : 'rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700';

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

                statusEl.textContent = 'GPS Unavailable';

                statusEl.className = 'rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500';

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

                    alert(message);

                    button.disabled = false;

                    button.innerHTML = originalText;

                    return;

                }

                window.location.reload();

            } catch (error) {

                console.error(error);

                alert('Terjadi kesalahan. Silakan coba lagi.');

                button.disabled = false;

                button.innerHTML = originalText;

            }

        }

        checkInBtn?.addEventListener('click', () => {

            submitAttendance(

                '<?php echo e(route("employee.attendance.check-in")); ?>',

                checkInBtn

            );

        });

        checkOutBtn?.addEventListener('click', () => {

            submitAttendance(

                '<?php echo e(route("employee.attendance.check-out")); ?>',

                checkOutBtn

            );

        });

    }

    if (window.lucide) {

        lucide.createIcons();

    }

});

</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/attendance/index.blade.php ENDPATH**/ ?>