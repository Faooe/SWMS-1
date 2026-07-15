<x-ui.card>

    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Date</th>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Type</th>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Check In</th>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Check Out</th>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>

                </tr>

            </thead>

            <tbody>

                @forelse($history as $item)

                    <tr class="border-t">

                        <td class="px-4 py-4 text-sm">

                            {{ \Carbon\Carbon::parse($item->attendance_date)->translatedFormat('d M Y') }}

                        </td>

                        <td class="px-4 py-4 text-sm">

                            {{ $item->attendance_type }}

                        </td>

                        <td class="px-4 py-4 text-sm">

                            {{ $item->check_in_time ?? '-' }}

                        </td>

                        <td class="px-4 py-4 text-sm">

                            {{ $item->check_out_time ?? '-' }}

                        </td>

                        <td class="px-4 py-4">

                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">

                                {{ $item->attendance_status }}

                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="py-8 text-center text-sm text-slate-500">

                            Belum ada riwayat absensi.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">

        {{ $history->links() }}

    </div>

</x-ui.card>