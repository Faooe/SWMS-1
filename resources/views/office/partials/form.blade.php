<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">

        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50">

            <i
                data-lucide="building-2"
                class="h-5 w-5 text-blue-600">
            </i>

        </div>

        <div>

            <h2 class="text-base font-black text-slate-900">

                Informasi Office

            </h2>

            <p class="mt-0.5 text-xs text-slate-500">

                Kelola identitas office. Koordinat, radius attendance, dan pengaturan GPS tersedia pada bagian peta.

            </p>

        </div>

    </div>

    <div class="p-5 sm:p-6">
    <div class="grid gap-5 sm:grid-cols-2">

        {{-- Office Code --}}
        <div>

            <label
                for="code"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Kode Office
                <span class="text-red-500">*</span>

            </label>

            <input

                id="code"

                type="text"

                name="code"

                value="{{ old('code', $office->code ?? '') }}"

                placeholder="Contoh: HQ-001"

                class="w-full rounded-2xl border border-slate-300 px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

            @error('code')

                <p class="mt-2 text-sm text-red-500">

                    {{ $message }}

                </p>

            @enderror

        </div>

        {{-- Office Name --}}
        <div>

            <label
                for="name"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Nama Office
                <span class="text-red-500">*</span>

            </label>

            <input

                id="name"

                type="text"

                name="name"

                value="{{ old('name', $office->name ?? '') }}"

                placeholder="Contoh: Kantor Pusat Banjarbaru"

                class="w-full rounded-2xl border border-slate-300 px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

            @error('name')

                <p class="mt-2 text-sm text-red-500">

                    {{ $message }}

                </p>

            @enderror

        </div>

        {{-- Province --}}
        <div>

            <label
                for="province"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Provinsi

            </label>

            <input

                id="province"

                type="text"

                name="province"

                value="{{ old('province', $office->province ?? '') }}"

                placeholder="Terisi otomatis dari peta"

                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

        </div>

        {{-- City --}}
        <div>

            <label
                for="city"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Kota

            </label>

            <input

                id="city"

                type="text"

                name="city"

                value="{{ old('city', $office->city ?? '') }}"

                placeholder="Terisi otomatis dari peta"

                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

        </div>

        {{-- Postal Code --}}
        <div>

            <label
                for="postal_code"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Kode Pos

            </label>

            <input

                id="postal_code"

                type="text"

                name="postal_code"

                value="{{ old('postal_code', $office->postal_code ?? '') }}"

                placeholder="Terisi otomatis dari peta"

                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

        </div>

        {{-- Timezone --}}
        <div>

            <label
                for="timezone"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Timezone

            </label>

            <select

                id="timezone"

                name="timezone"

                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                <option
                    value="Asia/Jakarta"
                    @selected(old('timezone', $office->timezone ?? '') == 'Asia/Jakarta')>

                    Asia / Jakarta (WIB)

                </option>

                <option
                    value="Asia/Makassar"
                    @selected(old('timezone', $office->timezone ?? 'Asia/Makassar') == 'Asia/Makassar')>

                    Asia / Makassar (WITA)

                </option>

                <option
                    value="Asia/Jayapura"
                    @selected(old('timezone', $office->timezone ?? '') == 'Asia/Jayapura')>

                    Asia / Jayapura (WIT)

                </option>

            </select>

        </div>

    </div>

    {{-- Address --}}
    <div class="mt-5">

        <label
            for="address"
            class="mb-2 block text-sm font-semibold text-slate-700">

            Alamat Office

        </label>

        <textarea

            id="address"

            name="address"

            rows="4"

            placeholder="Terisi otomatis dari peta"

            class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ old('address', $office->address ?? '') }}</textarea>

    </div>

    {{-- Information --}}
    <div
        class="mt-5 rounded-2xl border border-blue-100 bg-blue-50/70 p-4">

        <div class="flex items-start gap-3">

            <i
                data-lucide="info"
                class="mt-1 h-5 w-5 text-blue-600">
            </i>

            <div>

                <h4 class="font-semibold text-blue-700">

                    Informasi

                </h4>

                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-blue-700">

                    <li>
                        Lokasi office dipilih melalui peta interaktif.
                    </li>

                    <li>
                        Provinsi, kota, kode pos, dan alamat dapat terisi otomatis dari lokasi yang dipilih.
                    </li>

                    <li>
                        Radius attendance diatur melalui bagian peta.
                    </li>

                    <li>
                        Employee hanya dapat check-in di dalam area office yang ditentukan.
                    </li>

                </ul>

            </div>

        </div>

    </div>
    </div>

</section>
