<x-ui.card class="space-y-6">

    @if($successMessage)

        <div class="rounded-2xl bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ $successMessage }}
        </div>

    @endif

    <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Department Code <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            wire:model="code"
            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

        @error('code')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror

    </div>

    <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Department Name <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            wire:model="name"
            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

        @error('name')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror

    </div>

    <div>

        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Description
        </label>

        <textarea
            wire:model="description"
            rows="3"
            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></textarea>

        @error('description')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror

    </div>

    <label class="flex items-center gap-3">

        <input
            type="checkbox"
            wire:model="is_active"
            class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">

        <span class="text-sm font-medium text-slate-700">Active</span>

    </label>

    <div class="flex justify-end gap-3">

        <a
            href="{{ route('departments.index') }}"
            class="rounded-xl border border-slate-300 bg-white px-6 py-3 font-medium text-slate-700 shadow-sm transition hover:bg-slate-100">
            Kembali
        </a>

        <button
            type="button"
            wire:click="save"
            wire:loading.attr="disabled"
            class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:opacity-60">

            <span wire:loading.remove wire:target="save">Update Department</span>
            <span wire:loading wire:target="save">Menyimpan...</span>

        </button>

    </div>

</x-ui.card>
