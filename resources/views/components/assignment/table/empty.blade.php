<div class="rounded-3xl border border-dashed border-slate-300 bg-white py-20 text-center">

    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">

        <i
            data-lucide="clipboard-list"
            class="h-10 w-10 text-slate-400">

        </i>

    </div>

    <h3 class="mt-6 text-xl font-bold text-slate-700">

        No Assignment Found

    </h3>

    <p class="mt-2 text-slate-500">

        Create your first assignment to start managing workforce tasks.

    </p>

    <a
        href="{{ route('assignments.create') }}"
        class="mt-8 inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700">

        <i data-lucide="plus"></i>

        New Assignment

    </a>

</div>