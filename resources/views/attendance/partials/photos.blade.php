<x-ui.card>

    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800">

            Attendance Photos

        </h2>

        <p class="mt-1 text-slate-500">

            Employee check-in and check-out documentation.

        </p>

    </div>

    <div class="grid gap-8 lg:grid-cols-2">

        {{-- Check In --}}
        <div>

            <h3 class="mb-4 font-semibold text-slate-700">

                Check In Photo

            </h3>

            @if($attendance->check_in_photo)

                <a
                    href="{{ asset('storage/'.$attendance->check_in_photo) }}"
                    target="_blank">

                    <img

                        src="{{ asset('storage/'.$attendance->check_in_photo) }}"

                        class="h-80 w-full rounded-2xl border border-slate-200 object-cover shadow-sm transition duration-300 hover:scale-[1.02] hover:shadow-lg"

                    >

                </a>

                <p class="mt-3 text-center text-sm text-slate-500">

                    Click image to preview

                </p>

            @else

                <div
                    class="flex h-80 flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50">

                    <i

                        data-lucide="camera"

                        class="h-14 w-14 text-slate-400">

                    </i>

                    <p class="mt-4 text-slate-500">

                        No Check In Photo

                    </p>

                </div>

            @endif

        </div>

        {{-- Check Out --}}
        <div>

            <h3 class="mb-4 font-semibold text-slate-700">

                Check Out Photo

            </h3>

            @if($attendance->check_out_photo)

                <a
                    href="{{ asset('storage/'.$attendance->check_out_photo) }}"
                    target="_blank">

                    <img

                        src="{{ asset('storage/'.$attendance->check_out_photo) }}"

                        class="h-80 w-full rounded-2xl border border-slate-200 object-cover shadow-sm transition duration-300 hover:scale-[1.02] hover:shadow-lg"

                    >

                </a>

                <p class="mt-3 text-center text-sm text-slate-500">

                    Click image to preview

                </p>

            @else

                <div
                    class="flex h-80 flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50">

                    <i

                        data-lucide="camera-off"

                        class="h-14 w-14 text-slate-400">

                    </i>

                    <p class="mt-4 text-slate-500">

                        No Check Out Photo

                    </p>

                </div>

            @endif

        </div>

    </div>

</x-ui.card>