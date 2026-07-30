@props([
    'assignment' => null,
])

<div class="flex items-center justify-end gap-4">

    <a
        href="{{ route('assignments.index') }}"
        class="rounded-2xl border border-slate-300 px-6 py-3 font-medium text-slate-700 transition hover:bg-slate-100">

        Cancel

    </a>

    <button
        type="submit"
        class="rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700">

        {{ $assignment ? 'Update Assignment' : 'Create Assignment' }}

    </button>

</div>