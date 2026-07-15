<x-ui.card>

    <form
        method="GET"
        action="{{ route('offices.index') }}">

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">

            {{-- Search --}}
            <div class="xl:col-span-2">

                <label class="mb-2 block text-sm font-medium text-slate-600">

                    Search

                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Office name, code..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-500 focus:ring focus:ring-blue-100">

            </div>

            {{-- Province --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-600">

                    Province

                </label>

                <select
                    name="province"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    <option value="">

                        All Province

                    </option>

                    @foreach($provinces as $province)

                        <option
                            value="{{ $province }}"
                            @selected(request('province')==$province)>

                            {{ $province }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- City --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-600">

                    City

                </label>

                <select
                    name="city"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    <option value="">

                        All City

                    </option>

                    @foreach($cities as $city)

                        <option
                            value="{{ $city }}"
                            @selected(request('city')==$city)>

                            {{ $city }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Status --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-600">

                    Status

                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    <option value="">

                        All Status

                    </option>

                    <option
                        value="1"
                        @selected(request('status')==='1')>

                        Active

                    </option>

                    <option
                        value="0"
                        @selected(request('status')==='0')>

                        Inactive

                    </option>

                </select>

            </div>

            {{-- Per Page --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-600">

                    Per Page

                </label>

                <select
                    name="per_page"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    @foreach([10,25,50,100] as $item)

                        <option
                            value="{{ $item }}"
                            @selected(request('per_page',10)==$item)>

                            {{ $item }}

                        </option>

                    @endforeach

                </select>

            </div>

        </div>

        <div class="mt-6 flex flex-wrap items-center gap-3">

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700">

                Search

            </button>

            <a
                href="{{ route('offices.index') }}"
                class="rounded-xl border border-slate-300 px-6 py-3 hover:bg-slate-100">

                Reset

            </a>

        </div>

    </form>

</x-ui.card>