<x-ui.card>

    <div class="mb-5 flex items-center justify-between">

        <div>

            <h2 class="text-xl font-semibold text-slate-800">

                Office List

            </h2>

            <p class="mt-1 text-sm text-slate-500">

                {{ $offices->total() }} office(s) found.

            </p>

        </div>

    </div>

    {{-- Responsive Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200">

        <div class="max-h-[520px] overflow-y-auto overflow-x-auto">

            <table class="min-w-full whitespace-nowrap">

                <thead class="sticky top-0 z-10 bg-slate-100">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">

                            Office

                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">

                            Code

                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">

                            Province

                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">

                            City

                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold uppercase text-slate-500">

                            Radius

                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold uppercase text-slate-500">

                            Employees

                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold uppercase text-slate-500">

                            Status

                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold uppercase text-slate-500">

                            Action

                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200 bg-white">

                    @forelse($offices as $office)

                        <tr class="transition hover:bg-slate-50">

                            {{-- Office --}}
                            <td class="px-6 py-4">

                                <div>

                                    <h3 class="font-semibold text-slate-800">

                                        {{ $office->name }}

                                    </h3>

                                    @if($office->is_head_office)

                                        <span class="mt-1 inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700">

                                            Head Office

                                        </span>

                                    @endif

                                </div>

                            </td>

                            {{-- Code --}}
                            <td class="px-6 py-4">

                                {{ $office->code }}

                            </td>

                            {{-- Province --}}
                            <td class="px-6 py-4">

                                {{ $office->province ?? '-' }}

                            </td>

                            {{-- City --}}
                            <td class="px-6 py-4">

                                {{ $office->city ?? '-' }}

                            </td>

                            {{-- Radius --}}
                            <td class="px-6 py-4 text-center">

                                {{ number_format($office->radius) }} m

                            </td>

                            {{-- Employee --}}
                            <td class="px-6 py-4 text-center">

                                {{ $office->employees_count }}

                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">

                                @if($office->is_active)

                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">

                                        Active

                                    </span>

                                @else

                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">

                                        Inactive

                                    </span>

                                @endif

                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4">

                                <div class="flex justify-center gap-2">

                                    <a
                                        href="{{ route('offices.show',$office) }}"
                                        class="rounded-lg bg-sky-100 p-2 text-sky-700 transition hover:bg-sky-200">

                                        <i data-lucide="eye" class="h-4 w-4"></i>

                                    </a>

                                    <a
                                        href="{{ route('offices.edit',$office) }}"
                                        class="rounded-lg bg-amber-100 p-2 text-amber-700 transition hover:bg-amber-200">

                                        <i data-lucide="pencil" class="h-4 w-4"></i>

                                    </a>

                                    <form
                                        action="{{ route('offices.destroy',$office) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this office?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="rounded-lg bg-red-100 p-2 text-red-700 transition hover:bg-red-200">

                                            <i data-lucide="trash-2" class="h-4 w-4"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="px-6 py-16 text-center text-slate-500">

                                No office data found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Pagination --}}
    <div class="mt-6">

        {{ $offices->withQueryString()->links() }}

    </div>

</x-ui.card>