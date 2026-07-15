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
    <div class="space-y-5">

        {{-- Verification --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Verification

            </span>

            @if($attendance->location_verified)

                <span class="rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">

                    ✓ Verified

                </span>

            @else

                <span class="rounded-full bg-red-100 px-4 py-1 text-sm font-semibold text-red-700">

                    ✕ Not Verified

                </span>

            @endif

        </div>

        <hr>

        {{-- Distance --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Employee Distance

            </span>

            <span class="font-semibold">

                {{ number_format($attendance->check_in_distance ?? 0,2) }} m

            </span>

        </div>

        <hr>

        {{-- Radius --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Allowed Radius

            </span>

            <span class="font-semibold">

                {{ $attendance->allowed_radius ?? '-' }} m

            </span>

        </div>

        <hr>

        {{-- Latitude --}}
        <div>

            <p class="text-sm text-slate-500">

                Latitude

            </p>

            <p class="font-mono text-sm">

                {{ $attendance->check_in_latitude }}

            </p>

        </div>

        {{-- Longitude --}}
        <div>

            <p class="text-sm text-slate-500">

                Longitude

            </p>

            <p class="font-mono text-sm">

                {{ $attendance->check_in_longitude }}

            </p>

        </div>

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