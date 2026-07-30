@props([
    'employee' => null,
])

<div class="flex items-center justify-between rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

    <a
        href="{{ route('employees.index') }}"
        class="inline-flex items-center rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 transition hover:bg-slate-100">

        Cancel

    </a>

    <button

        type="submit"

        class="inline-flex items-center rounded-2xl bg-blue-600 px-8 py-3 font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"

    >

        @if($employee)

            Update Employee

        @else

            Save Employee

        @endif

    </button>

</div>