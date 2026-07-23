<x-ui.card>

    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h2 class="text-xl font-bold text-slate-800">

                Save Changes

            </h2>

            <p class="mt-2 text-slate-500">

                Please review the office information before saving.

            </p>

        </div>

        <div class="flex flex-wrap items-center justify-end gap-3">

            <a
                href="{{ route('offices.index') }}"
                class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-6 py-3 font-medium text-slate-700 transition hover:bg-slate-100">

                Cancel

            </a>

            <button
                type="submit"
                class="inline-flex items-center rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow transition hover:bg-blue-700">

                <i
                    data-lucide="save"
                    class="mr-2 h-5 w-5">
                </i>

                Update Office

            </button>

        </div>

    </div>

</x-ui.card>

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', () => {

    if (window.lucide) {

        lucide.createIcons();

    }

});

</script>

@endpush