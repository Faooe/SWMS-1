<x-ui.card>

    <h2 class="text-2xl font-bold">

        Status Office

    </h2>

    <p class="mt-1 text-slate-500">

        Atur ketersediaan dan penanda kantor pusat.

    </p>

    <div class="mt-8 space-y-5">

        <label class="flex items-center gap-4">

            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active', $office->is_active))>

            <span>

                Office aktif

            </span>

        </label>

        <label class="flex items-center gap-4">

            <input
                type="checkbox"
                name="is_head_office"
                value="1"
                @checked(old('is_head_office', $office->is_head_office))>

            <span>

                Kantor pusat

            </span>

        </label>

    </div>

</x-ui.card>
