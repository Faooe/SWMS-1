<form
    method="GET"
    action="{{ route('assignments.index') }}"
    class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="grid gap-5 lg:grid-cols-5">

        {{-- Search --}}
        <div class="lg:col-span-2">

            <label
                class="mb-2 block text-sm font-semibold text-slate-700">

                Search

            </label>

            <input

                type="text"

                name="search"

                value="{{ request('search') }}"

                placeholder="Assignment Number / Title..."

                class="w-full rounded-2xl border border-slate-300 px-4 py-3
                       focus:border-blue-500
                       focus:ring-4
                       focus:ring-blue-100">

        </div>

        {{-- Priority --}}
        <div>

            <label
                class="mb-2 block text-sm font-semibold text-slate-700">

                Priority

            </label>

            <select

                name="priority"

                class="w-full rounded-2xl border border-slate-300 px-4 py-3">

                <option value="">

                    All

                </option>

                @foreach([
                    'Low',
                    'Medium',
                    'High',
                    'Critical'
                ] as $priority)

                    <option

                        value="{{ $priority }}"

                        @selected(request('priority')==$priority)>

                        {{ $priority }}

                    </option>

                @endforeach

            </select>

        </div>

        {{-- Status --}}
        <div>

            <label
                class="mb-2 block text-sm font-semibold text-slate-700">

                Status

            </label>

            <select

                name="status"

                class="w-full rounded-2xl border border-slate-300 px-4 py-3">

                <option value="">

                    All

                </option>

                @foreach([
                    'Draft',
                    'Assigned',
                    'In Progress',
                    'Completed',
                    'Cancelled'
                ] as $status)

                    <option

                        value="{{ $status }}"

                        @selected(request('status')==$status)>

                        {{ $status }}

                    </option>

                @endforeach

            </select>

        </div>

        {{-- Button --}}
        <div
            class="flex items-end gap-3">

            <button

                type="submit"

                class="flex-1 rounded-2xl bg-blue-600 px-5 py-3
                       font-semibold text-white
                       transition
                       hover:bg-blue-700">

                Filter

            </button>

            <a

                href="{{ route('assignments.index') }}"

                class="rounded-2xl border border-slate-300
                       px-5 py-3
                       font-semibold
                       hover:bg-slate-100">

                Reset

            </a>

        </div>

    </div>

</form>