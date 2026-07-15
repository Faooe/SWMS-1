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

    
    <div
        id="assignment-map"
        class="mb-6 h-[420px] overflow-hidden rounded-2xl border">
    </div>

    
    <div
        class="grid gap-6 md:grid-cols-2">

        <div>

            <p
                class="text-sm text-slate-500">

                Location

            </p>

            <h3
                class="mt-2 font-semibold">

                <?php echo e($assignment->location_name); ?>


            </h3>

            <p
                class="mt-2 text-sm text-slate-500">

                <?php echo e($assignment->address); ?>


            </p>

        </div>

        <div>

            <p
                class="text-sm text-slate-500">

                Allowed Radius

            </p>

            <h3
                class="mt-2 text-2xl font-bold text-blue-600">

                <?php echo e($assignment->radius); ?> m

            </h3>

        </div>

    </div>

</div>

<?php $__env->startPush('scripts'); ?>

<script>

document.addEventListener('DOMContentLoaded',()=>{

    const map=L.map('assignment-map').setView(

        [

            <?php echo e($assignment->latitude); ?>,

            <?php echo e($assignment->longitude); ?>


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

        <?php echo e($assignment->latitude); ?>,

        <?php echo e($assignment->longitude); ?>


    ];

    L.marker(point)

        .addTo(map)

        .bindPopup(

            "<?php echo e($assignment->title); ?>"

        )

        .openPopup();

    L.circle(

        point,

        {

            radius:<?php echo e($assignment->radius); ?>,

            color:'#2563eb',

            fillColor:'#3b82f6',

            fillOpacity:0.20

        }

    ).addTo(map);

});

</script>

<?php $__env->stopPush(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/assignments/partials/location.blade.php ENDPATH**/ ?>