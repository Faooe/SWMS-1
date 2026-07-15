@props([
    'employees'
])

<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

    <div class="max-h-[520px] overflow-y-auto overflow-x-auto">

        <table class="min-w-full">

            {{-- =========================
                Header
            ========================== --}}
            <thead class="sticky top-0 z-10 bg-slate-50">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Employee
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Department
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Position
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Status
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                        Action
                    </th>

                </tr>

            </thead>

            {{-- =========================
                Body
            ========================== --}}
            <tbody class="divide-y divide-slate-200">

                @forelse($employees as $employee)

                    <tr
                        class="transition duration-300 hover:bg-slate-50">

                        {{-- Employee --}}
                        <td class="px-6 py-5">

                            <div class="flex items-center gap-4">

                                <x-ui.avatar
                                    :employee="$employee"
                                    size="12" />

                                <div>

                                    <h3
                                        class="font-semibold text-slate-800">

                                        {{ $employee->full_name }}

                                    </h3>

                                    <p
                                        class="text-sm text-slate-500">

                                        {{ $employee->email }}

                                    </p>

                                </div>

                            </div>

                        </td>

                        {{-- Department --}}
                        <td class="px-6 py-5">

                            <span class="font-medium">

                                {{ $employee->currentEmployment?->department?->name ?? '-' }}

                            </span>

                        </td>

                        {{-- Position --}}
                        <td class="px-6 py-5">

                            <span class="font-medium">

                                {{ $employee->currentEmployment?->position?->name ?? '-' }}

                            </span>

                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-5">

                            <form
                                action="{{ route('employees.toggle-status', $employee) }}"
                                method="POST"
                                onsubmit="return confirm('{{ $employee->is_active ? 'Nonaktifkan' : 'Aktifkan' }} employee {{ $employee->full_name }}?')">

                                @csrf
                                @method('PATCH')

                                @if($employee->is_active)

                                    <button
                                        type="submit"
                                        title="Klik untuk menonaktifkan"
                                        class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700 transition hover:bg-green-200">

                                        <span
                                            class="h-2.5 w-2.5 rounded-full bg-green-500">
                                        </span>

                                        Active

                                    </button>

                                @else

                                    <button
                                        type="submit"
                                        title="Klik untuk mengaktifkan"
                                        class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700 transition hover:bg-red-200">

                                        <span
                                            class="h-2.5 w-2.5 rounded-full bg-red-500">
                                        </span>

                                        Inactive

                                    </button>

                                @endif

                            </form>

                        </td>

                        {{-- Action --}}
                        <td class="px-6 py-5">

                            <div
                                class="flex items-center justify-center gap-2">

                                {{-- View --}}
                                <a
                                    href="{{ route('employees.show',$employee) }}"
                                    title="View Employee"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-600 transition hover:bg-sky-600 hover:text-white">

                                    <i
                                        data-lucide="eye"
                                        class="h-4 w-4">
                                    </i>

                                </a>

                                {{-- Edit --}}
                                <a
                                    href="{{ route('employees.edit',$employee) }}"
                                    title="Edit Employee"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition hover:bg-amber-500 hover:text-white">

                                    <i
                                        data-lucide="pencil"
                                        class="h-4 w-4">
                                    </i>

                                </a>

                                {{-- Delete --}}
                                <form
                                    action="{{ route('employees.destroy',$employee) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete employee {{ $employee->full_name }}?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        title="Delete Employee"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-600 transition hover:bg-red-600 hover:text-white">

                                        <i
                                            data-lucide="trash-2"
                                            class="h-4 w-4">
                                        </i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="py-20">

                            <div
                                class="flex flex-col items-center justify-center">

                                <i
                                    data-lucide="users-round"
                                    class="mb-4 h-14 w-14 text-slate-300">
                                </i>

                                <h3
                                    class="text-lg font-semibold text-slate-700">

                                    No Employee Found

                                </h3>

                                <p
                                    class="mt-2 text-sm text-slate-400">

                                    There are no employee records available.

                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
    <div
        class="border-t border-slate-200 bg-slate-50 px-6 py-4">

        {{ $employees->withQueryString()->links() }}

    </div>

</div>