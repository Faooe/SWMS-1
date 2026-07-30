<x-ui.card>

    <h3 class="mb-5 text-lg font-semibold text-slate-800">
        Quick Actions
    </h3>

    <div class="grid grid-cols-2 gap-4">

        <a
            href="{{ route('employee.assignments.index') }}"
            class="flex flex-col items-center gap-2 rounded-2xl border border-slate-200 p-5 text-center transition hover:border-blue-300 hover:bg-blue-50">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                <i data-lucide="clipboard-list" class="h-5 w-5"></i>
            </div>

            <span class="text-sm font-semibold text-slate-700">Assignment</span>

        </a>

        <a
            href="{{ route('employee.attendance.index') }}"
            class="flex flex-col items-center gap-2 rounded-2xl border border-slate-200 p-5 text-center transition hover:border-green-300 hover:bg-green-50">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-600">
                <i data-lucide="calendar-check" class="h-5 w-5"></i>
            </div>

            <span class="text-sm font-semibold text-slate-700">Attendance</span>

        </a>

        <a
            href="{{ route('employee.attendance.history') }}"
            class="flex flex-col items-center gap-2 rounded-2xl border border-slate-200 p-5 text-center transition hover:border-purple-300 hover:bg-purple-50">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-100 text-purple-600">
                <i data-lucide="history" class="h-5 w-5"></i>
            </div>

            <span class="text-sm font-semibold text-slate-700">History</span>

        </a>

        <a
            href="{{ route('employee.profile') }}"
            class="flex flex-col items-center gap-2 rounded-2xl border border-slate-200 p-5 text-center transition hover:border-orange-300 hover:bg-orange-50">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                <i data-lucide="user-round" class="h-5 w-5"></i>
            </div>

            <span class="text-sm font-semibold text-slate-700">Profile</span>

        </a>

    </div>

</x-ui.card>
