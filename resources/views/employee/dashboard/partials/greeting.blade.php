<div class="overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-blue-600 to-cyan-500 p-8 text-white shadow-sm">

    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

        <div class="flex items-center gap-4">

            <div class="rounded-full ring-4 ring-white/30">

                <x-ui.avatar
                    :employee="$employee"
                    size="16" />

            </div>

            <div>

                <h1 class="text-2xl font-bold lg:text-3xl">
                    Halo, {{ $employee->full_name }}
                </h1>

                <p class="mt-1 text-sm text-blue-100 lg:text-base">
                    Selamat datang kembali. {{ now()->translatedFormat('l, d F Y') }}
                </p>

            </div>

        </div>

        <div class="flex items-center gap-3 lg:flex-col lg:items-end">

            <span class="text-xs font-medium uppercase tracking-wide text-blue-100">
                Status Hari Ini
            </span>

            @if($todayAttendance)

                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-semibold backdrop-blur">
                    <span class="h-2.5 w-2.5 rounded-full bg-green-300"></span>
                    {{ $todayAttendance->attendance_status }}
                </span>

            @else

                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-semibold backdrop-blur">
                    <span class="h-2.5 w-2.5 rounded-full bg-red-300"></span>
                    Belum Check In
                </span>

            @endif

        </div>

    </div>

</div>
