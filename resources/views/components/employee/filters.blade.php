@props([
    'departments',
])

<form
    method="GET"
    class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="grid gap-4 md:grid-cols-4">

        {{-- Search --}}
        <x-ui.input

            name="search"

            label="Search"

            placeholder="Employee name..."

            :value="request('search')"

        />

        {{-- Department --}}
        <x-ui.select

            name="department"

            label="Department"

            :options="$departments"

            :selected="request('department')"

            placeholder="All Department"

        />

        {{-- Status --}}
        <div>

            <label
                class="mb-2 block text-sm font-semibold">

                Status

            </label>

            <select

                name="is_active"

                class="w-full rounded-2xl border border-slate-300 px-4 py-3">

                <option value="">

                    All

                </option>

                <option

                    value="1"

                    @selected(request('is_active')==='1')>

                    Active

                </option>

                <option

                    value="0"

                    @selected(request('is_active')==='0')>

                    Inactive

                </option>

            </select>

        </div>

        {{-- Button --}}
        <div
            class="flex items-end gap-3">

            <button

                class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white">

                Search

            </button>

            <a

                href="{{ route('employees.index') }}"

                class="rounded-xl border px-6 py-3">

                Reset

            </a>

        </div>

    </div>

</form>