@props([
    'employee' => null,
])

<x-employee.section-card
    title="Personal Information"
    description="Basic employee identity information."
    icon="user">

    <div class="grid gap-6 md:grid-cols-2">

        {{-- Employee Number --}}
        <x-ui.input
            name="employee_number"
            label="Employee Number"
            :value="$employee?->employee_number"
            required />

        {{-- Full Name --}}
        <x-ui.input
            name="full_name"
            label="Full Name"
            :value="$employee?->full_name"
            required />

        {{-- Email --}}
        <x-ui.input
            name="email"
            type="email"
            label="Email"
            :value="$employee?->email"
            required />

        {{-- Phone --}}
        <x-ui.input
            name="phone"
            label="Phone Number"
            :value="$employee?->phone" />

        {{-- Gender --}}
        <div>

            <label
                for="gender"
                class="mb-2 flex items-center gap-1 text-sm font-semibold text-slate-700">

                Gender

                <span class="text-red-500">*</span>

            </label>

            <select
                id="gender"
                name="gender"
                required
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @error('gender') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror">

                <option value="">
                    Select Gender
                </option>

                <option
                    value="Male"
                    @selected(old('gender', $employee?->gender) == 'Male')>

                    Male

                </option>

                <option
                    value="Female"
                    @selected(old('gender', $employee?->gender) == 'Female')>

                    Female

                </option>

            </select>

            @error('gender')
                <p class="mt-2 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>

        {{-- Marital Status --}}
        <div>

            <label
                for="marital_status"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Marital Status

            </label>

            <select
                id="marital_status"
                name="marital_status"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @error('marital_status') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror">

                <option value="">
                    Select Status
                </option>

                <option
                    value="Single"
                    @selected(old('marital_status', $employee?->marital_status) == 'Single')>

                    Single

                </option>

                <option
                    value="Married"
                    @selected(old('marital_status', $employee?->marital_status) == 'Married')>

                    Married

                </option>

                <option
                    value="Divorced"
                    @selected(old('marital_status', $employee?->marital_status) == 'Divorced')>

                    Divorced

                </option>

            </select>

            @error('marital_status')
                <p class="mt-2 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>

        {{-- Employee Status --}}
        <div>

            <label
                for="is_active"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Employee Status

            </label>

            <select
                id="is_active"
                name="is_active"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                <option
                    value="1"
                    {{ old('is_active', (string) ($employee?->is_active ? 1 : 0)) == '1' ? 'selected' : '' }}>

                    Active

                </option>

                <option
                    value="0"
                    {{ old('is_active', (string) ($employee?->is_active ? 1 : 0)) == '0' ? 'selected' : '' }}>

                    Inactive

                </option>

            </select>

        </div>

        {{-- Identity Number --}}
        <div>

            <x-ui.input
                name="identity_number"
                label="Identity Number"
                placeholder="Kosongkan untuk generate otomatis"
                maxlength="9"
                inputmode="numeric"
                pattern="[0-9]*"
                :value="$employee?->identity_number" />

            <p class="mt-2 text-xs text-slate-400">
                Maksimal 9 digit angka. Jika dikosongkan, nomor akan dibuat otomatis oleh sistem.
            </p>

        </div>

        {{-- Birth Place --}}
        <x-ui.input
            name="birth_place"
            label="Birth Place"
            :value="$employee?->birth_place" />

        {{-- Birth Date --}}
        <x-ui.input
            name="birth_date"
            type="date"
            label="Birth Date"
            :value="$employee?->birth_date?->format('Y-m-d')" />

    </div>

    {{-- Address --}}
    <div class="mt-6">

        <label
            for="address"
            class="mb-2 block text-sm font-semibold text-slate-700">

            Address

        </label>

        <textarea
            id="address"
            name="address"
            rows="4"
            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @error('address') border-red-500 focus:border-red-500 focus:ring-red-100 @enderror">{{ old('address', $employee?->address) }}</textarea>

        @error('address')
            <p class="mt-2 text-sm text-red-500">
                {{ $message }}
            </p>
        @enderror

    </div>

</x-employee.section-card>