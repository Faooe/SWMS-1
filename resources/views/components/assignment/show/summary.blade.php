@props([
    'assignment'
])

<div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="flex items-center gap-3">

                <h1 class="text-3xl font-bold text-slate-800">

                    {{ $assignment->title }}

                </h1>

                <span
                    class="rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">

                    {{ $assignment->status }}

                </span>

            </div>

            <p class="mt-3 text-slate-500">

                {{ $assignment->description }}

            </p>

        </div>

        <div>

            <span
                class="rounded-full bg-red-100 px-4 py-2 text-sm font-bold text-red-700">

                {{ $assignment->priority }}

            </span>

        </div>

    </div>

</div>