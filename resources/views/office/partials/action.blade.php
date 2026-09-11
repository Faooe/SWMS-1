<x-ui.card>

    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h2 class="text-xl font-bold text-slate-800">

                Simpan Perubahan

            </h2>

            <p class="mt-2 text-slate-500">

                Periksa kembali informasi office sebelum menyimpan.

            </p>

        </div>

        <div class="flex flex-wrap items-center justify-end gap-3">

            <a
                href="{{ route('offices.index') }}"
                class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-6 py-3 font-medium text-slate-700 transition hover:bg-slate-100">

                Batal

            </a>

            <button
                type="submit"
                class="inline-flex items-center rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow transition hover:bg-blue-700">

                <i
                    data-lucide="save"
                    class="mr-2 h-5 w-5">
                </i>

                Simpan Office

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
