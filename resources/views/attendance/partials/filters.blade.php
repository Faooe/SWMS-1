<form
    method="GET"
    class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

    <div
        class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-5">

        {{-- Search --}}
        <div>

            <label
                class="mb-2 block text-sm font-medium text-slate-600">

                Search Employee

            </label>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Employee Name..."
                class="w-full rounded-xl border-slate-300">

        </div>

        {{-- Office --}}
        <div>

            <label
                class="mb-2 block text-sm font-medium text-slate-600">

                Office

            </label>

            <select
                name="office"
                class="w-full rounded-xl border-slate-300">

                <option value="">

                    All Office

                </option>

            </select>

        </div>

        {{-- Status --}}
        <div>

            <label
                class="mb-2 block text-sm font-medium text-slate-600">

                Status

            </label>

            <select
                name="status"
                class="w-full rounded-xl border-slate-300">

                <option value="">

                    All Status

                </option>

                <option value="Present">

                    Present

                </option>

                <option value="Late">

                    Late

                </option>

                <option value="Leave">

                    Leave

                </option>

                <option value="Permission">

                    Permission

                </option>

                <option value="Absent">

                    Absent

                </option>

            </select>

        </div>

        {{-- Date --}}
        <div>

            <label
                class="mb-2 block text-sm font-medium text-slate-600">

                Attendance Date

            </label>

            <input
                type="date"
                name="date"
                value="{{ request('date') }}"
                class="w-full rounded-xl border-slate-300">

        </div>

        {{-- Button --}}
        <div
            class="flex items-end gap-3">

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700">

                Filter

            </button>

            <a
                href="{{ route('attendance.index') }}"
                class="rounded-xl border border-slate-300 px-5 py-3 font-semibold">

                Reset

            </a>

        </div>

    </div>

</form>