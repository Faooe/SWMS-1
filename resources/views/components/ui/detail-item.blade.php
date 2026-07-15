@props([
    'label',
    'value' => '-',
])

<div>

    <label
        class="mb-2 block text-sm font-semibold text-slate-500">

        {{ $label }}

    </label>

    <div
        class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-800">

        {{ filled($value) ? $value : '-' }}

    </div>

</div>