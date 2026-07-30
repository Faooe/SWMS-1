<x-ui.card>

    {{-- Header --}}
    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800">

            GPS Validation

        </h2>

        <p class="mt-1 text-sm text-slate-500">

            Employee location verification.

        </p>

    </div>

    {{-- Information --}}
    <div class="grid gap-5 sm:grid-cols-2">

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-500">
                Verification
            </label>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">

                @if($attendance->location_verified)

                    <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                        ✓ Verified
                    </span>

                @else

                    <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                        ✕ Not Verified
                    </span>

                @endif

            </div>
        </div>

        <x-ui.detail-item
            label="Employee Distance"
            :value="number_format($attendance->check_in_distance ?? 0, 2) . ' m'" />

        <x-ui.detail-item
            label="Allowed Radius"
            :value="$attendance->allowed_radius !== null ? $attendance->allowed_radius . ' m' : null" />

        <x-ui.detail-item
            label="Latitude"
            :value="$attendance->check_in_latitude" />

        <x-ui.detail-item
            label="Longitude"
            :value="$attendance->check_in_longitude" />

    </div>

    {{-- Map --}}
    @if($attendance->check_in_latitude && $attendance->check_in_longitude)

        <div class="mt-6 overflow-hidden rounded-2xl border">

            <iframe
                width="100%"
                height="280"
                frameborder="0"
                scrolling="no"
                loading="lazy"
                src="https://www.openstreetmap.org/export/embed.html?bbox={{ $attendance->check_in_longitude-0.002 }},{{ $attendance->check_in_latitude-0.002 }},{{ $attendance->check_in_longitude+0.002 }},{{ $attendance->check_in_latitude+0.002 }}&layer=mapnik&marker={{ $attendance->check_in_latitude }},{{ $attendance->check_in_longitude }}">
            </iframe>

        </div>

        <a

            href="https://www.google.com/maps?q={{ $attendance->check_in_latitude }},{{ $attendance->check_in_longitude }}"

            target="_blank"

            class="mt-4 inline-flex items-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">

            Open Google Maps

        </a>

    @endif

</x-ui.card>