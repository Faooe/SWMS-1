@props([
    'employee' => null,
])

<x-employee.section-card
    title="Account Information"
    description="Employee login account."
    icon="shield-check">

    <div class="grid gap-6 md:grid-cols-2">

        {{-- Username --}}
        <x-ui.input
            name="username"
            label="Username"
            :value="$employee?->user?->username"
            required />

        {{-- Email Login --}}
        <x-ui.input
            name="user_email"
            type="email"
            label="Login Email"
            :value="$employee?->user?->email"
            required />

        {{-- Password --}}
        <x-ui.input
            name="password"
            type="password"
            label="{{ $employee ? 'New Password (optional)' : 'Password' }}"
            :required="!$employee"
            placeholder="{{ $employee ? 'Leave blank if unchanged' : 'Minimum 8 characters' }}" />

        {{-- User Status --}}
        <div>

            <label
                for="user_is_active"
                class="mb-2 block text-sm font-semibold text-slate-700">

                User Status

            </label>

            <select

                id="user_is_active"

                name="user_is_active"

                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm
                       focus:border-blue-500
                       focus:ring-4
                       focus:ring-blue-100">

                <option
                    value="1"
                    @selected(old('user_is_active',$employee?->user?->is_active ?? true))>

                    Active

                </option>

                <option
                    value="0"
                    @selected(old('user_is_active',$employee?->user?->is_active ?? true)==false)>

                    Inactive

                </option>

            </select>

            @error('user_is_active')

                <p class="mt-2 text-sm text-red-500">

                    {{ $message }}

                </p>

            @enderror

        </div>

    </div>

</x-employee.section-card>