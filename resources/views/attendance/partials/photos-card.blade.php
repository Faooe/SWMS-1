<x-ui.card>

    {{-- Header --}}
    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800">

            Attendance Photos

        </h2>

        <p class="mt-1 text-sm text-slate-500">

            Employee attendance documentation.

        </p>

    </div>

    <div class="grid gap-6 md:grid-cols-2">

        {{-- Check In --}}
        <div>

            <h3 class="mb-3 font-semibold text-slate-700">

                Check In

            </h3>

            @if($attendance->check_in_photo)

                <button
                    onclick="openPhoto('{{ asset('storage/'.$attendance->check_in_photo) }}')"
                    class="group w-full">

                    <div class="overflow-hidden rounded-2xl border">

                        <img

                            src="{{ asset('storage/'.$attendance->check_in_photo) }}"

                            class="h-72 w-full object-cover transition duration-300 group-hover:scale-110">

                    </div>

                    <p class="mt-3 text-sm text-blue-600">

                        Click to Preview

                    </p>

                </button>

            @else

                <div class="flex h-72 items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50">

                    <div class="text-center">

                        <i
                            data-lucide="camera"
                            class="mx-auto h-10 w-10 text-slate-400">

                        </i>

                        <p class="mt-3 text-slate-400">

                            No Check In Photo

                        </p>

                    </div>

                </div>

            @endif

        </div>

        {{-- Check Out --}}
        <div>

            <h3 class="mb-3 font-semibold text-slate-700">

                Check Out

            </h3>

            @if($attendance->check_out_photo)

                <button
                    onclick="openPhoto('{{ asset('storage/'.$attendance->check_out_photo) }}')"
                    class="group w-full">

                    <div class="overflow-hidden rounded-2xl border">

                        <img

                            src="{{ asset('storage/'.$attendance->check_out_photo) }}"

                            class="h-72 w-full object-cover transition duration-300 group-hover:scale-110">

                    </div>

                    <p class="mt-3 text-sm text-blue-600">

                        Click to Preview

                    </p>

                </button>

            @else

                <div class="flex h-72 items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50">

                    <div class="text-center">

                        <i
                            data-lucide="camera"
                            class="mx-auto h-10 w-10 text-slate-400">

                        </i>

                        <p class="mt-3 text-slate-400">

                            No Check Out Photo

                        </p>

                    </div>

                </div>

            @endif

        </div>

    </div>

</x-ui.card>