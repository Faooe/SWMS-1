@props([
    'statistics'
])

<div
    class="grid gap-6 md:grid-cols-3">

    <div
        class="rounded-3xl border bg-white p-6 shadow-sm">

        <p
            class="text-sm text-slate-500">

            Total Assignment

        </p>

        <h2
            class="mt-2 text-3xl font-bold">

            {{ $statistics['assignment'] }}

        </h2>

    </div>

    <div
        class="rounded-3xl border bg-white p-6 shadow-sm">

        <p
            class="text-sm text-slate-500">

            Completed

        </p>

        <h2
            class="mt-2 text-3xl font-bold text-green-600">

            {{ $statistics['completed'] }}

        </h2>

    </div>

    <div
        class="rounded-3xl border bg-white p-6 shadow-sm">

        <p
            class="text-sm text-slate-500">

            Attendance

        </p>

        <h2
            class="mt-2 text-3xl font-bold text-blue-600">

            {{ $statistics['attendance'] }}

        </h2>

    </div>

</div>