<x-ui.card>

    <div class="flex items-center justify-between">

        <div>

            <h2 class="text-xl font-bold text-slate-800">

                GPS Validation

            </h2>

            <p class="mt-1 text-slate-500">

                Employee attendance location information.

            </p>

        </div>

        @if($attendance->location_verified)

            <span
                class="rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">

                Verified

            </span>

        @else

            <span
                class="rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-700">

                Not Verified

            </span>

        @endif

    </div>

    <div class="mt-8 grid gap-6 md:grid-cols-2">

        <div>

            <p class="text-sm text-slate-500">

                Latitude

            </p>

            <h3 class="mt-1 font-semibold">

                {{ $attendance->check_in_latitude ?? '-' }}

            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Longitude

            </p>

            <h3 class="mt-1 font-semibold">

                {{ $attendance->check_in_longitude ?? '-' }}

            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Distance

            </p>

            <h3 class="mt-1 font-semibold">

                {{ number_format($attendance->check_in_distance ?? 0,2) }} Meter

            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Allowed Radius

            </p>

            <h3 class="mt-1 font-semibold">

                {{ $attendance->allowed_radius ?? '-' }} Meter

            </h3>

        </div>

    </div>

    @if($attendance->check_in_latitude && $attendance->check_in_longitude)

        <div class="mt-8">

            <a

                href="https://maps.google.com/?q={{ $attendance->check_in_latitude }},{{ $attendance->check_in_longitude }}"

                target="_blank"

                class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-3 text-white transition hover:bg-blue-700">

                <i
                    data-lucide="map-pin"
                    class="mr-2 h-4 w-4">
                </i>

                Open Google Maps

            </a>

        </div>

    @endif

</x-ui.card>