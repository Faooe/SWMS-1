<div
    class="rounded-3xl bg-white p-8 shadow">

    <div class="mb-6 flex items-center gap-3">

        <div
            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100">

            <i
                data-lucide="file-text"
                class="h-6 w-6 text-blue-600">
            </i>

        </div>

        <div>

            <h2
                class="text-xl font-bold text-slate-800">

                Description

            </h2>

            <p
                class="text-sm text-slate-500">

                Assignment information from administrator.

            </p>

        </div>

    </div>

    @if($assignment->description)

        <div
            class="rounded-2xl border border-slate-200 bg-slate-50 p-6 leading-8 text-slate-700">

            {!! nl2br(e($assignment->description)) !!}

        </div>

    @else

        <div
            class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 py-12 text-center">

            <i
                data-lucide="file-x"
                class="mx-auto h-10 w-10 text-slate-300">
            </i>

            <h3
                class="mt-4 font-semibold">

                No Description

            </h3>

            <p
                class="mt-2 text-sm text-slate-500">

                Administrator didn't provide additional information.

            </p>

        </div>

    @endif

</div>