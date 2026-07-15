<x-ui.card>

    {{-- Header --}}
    <div class="mb-8 flex items-start gap-4">

        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100">

            <i
                data-lucide="building-2"
                class="h-7 w-7 text-blue-600">
            </i>

        </div>

        <div>

            <h2 class="text-2xl font-bold text-slate-800">

                Office Information

            </h2>

            <p class="mt-2 text-slate-500">

                Enter the office identity. Office coordinates, attendance radius, and GPS settings are configured in the map section below.

            </p>

        </div>

    </div>

    <div class="grid gap-6 lg:grid-cols-2">

        {{-- Office Code --}}
        <div>

            <label
                for="code"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Office Code
                <span class="text-red-500">*</span>

            </label>

            <input

                id="code"

                type="text"

                name="code"

                value="{{ old('code', $office->code ?? '') }}"

                placeholder="Example : HQ-001"

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

                Office Name
                <span class="text-red-500">*</span>

            </label>

            <input

                id="name"

                type="text"

                name="name"

                value="{{ old('name', $office->name ?? '') }}"

                placeholder="Example : Head Office Banjarbaru"

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

                Province

            </label>

            <input

                id="province"

                type="text"

                name="province"

                value="{{ old('province', $office->province ?? '') }}"

                placeholder="Automatically filled from map"

                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

        </div>

        {{-- City --}}
        <div>

            <label
                for="city"
                class="mb-2 block text-sm font-semibold text-slate-700">

                City

            </label>

            <input

                id="city"

                type="text"

                name="city"

                value="{{ old('city', $office->city ?? '') }}"

                placeholder="Automatically filled from map"

                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

        </div>

        {{-- Postal Code --}}
        <div>

            <label
                for="postal_code"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Postal Code

            </label>

            <input

                id="postal_code"

                type="text"

                name="postal_code"

                value="{{ old('postal_code', $office->postal_code ?? '') }}"

                placeholder="Automatically filled from map"

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
    <div class="mt-8">

        <label
            for="address"
            class="mb-2 block text-sm font-semibold text-slate-700">

            Office Address

        </label>

        <textarea

            id="address"

            name="address"

            rows="5"

            placeholder="Automatically filled from map"

            class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ old('address', $office->address ?? '') }}</textarea>

    </div>

    {{-- Information --}}
    <div
        class="mt-8 rounded-2xl border border-blue-200 bg-blue-50 p-5">

        <div class="flex items-start gap-3">

            <i
                data-lucide="info"
                class="mt-1 h-5 w-5 text-blue-600">
            </i>

            <div>

                <h4 class="font-semibold text-blue-700">

                    Information

                </h4>

                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-blue-700">

                    <li>
                        Office location is selected using the interactive map.
                    </li>

                    <li>
                        Province, city, postal code, and address can be filled automatically from the selected location.
                    </li>

                    <li>
                        Attendance radius is configured in the map section.
                    </li>

                    <li>
                        Employees can only check in within the configured office radius.
                    </li>

                </ul>

            </div>

        </div>

    </div>

</x-ui.card>