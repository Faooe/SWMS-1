<div
    class="mt-8 flex items-center justify-between">

    <div>

        <h2
            class="text-2xl font-bold text-slate-800">

            Assignment List

        </h2>

        <p
            class="mt-1 text-slate-500">

            Manage all workforce assignments.

        </p>

    </div>

    <a

        href="{{ route('assignments.create') }}"

        class="inline-flex items-center gap-2 rounded-2xl
               bg-blue-600 px-5 py-3
               font-semibold text-white
               transition hover:bg-blue-700">

        <i
            data-lucide="plus"
            class="h-5 w-5">

        </i>

        New Assignment

    </a>

</div>