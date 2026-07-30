<x-ui.card>

    <h2 class="text-2xl font-bold">

        Office Status

    </h2>

    <p class="mt-1 text-slate-500">

        Configure office availability.

    </p>

    <div class="mt-8 space-y-5">

        <label class="flex items-center gap-4">

            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active',true))>

            <span>

                Active Office

            </span>

        </label>

        <label class="flex items-center gap-4">

            <input
                type="checkbox"
                name="is_head_office"
                value="1"
                @checked(old('is_head_office'))>

            <span>

                Head Office

            </span>

        </label>

    </div>

</x-ui.card>