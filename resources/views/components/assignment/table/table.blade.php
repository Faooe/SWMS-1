@props(['assignments'])

@if($assignments->count())
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3.5">Assignment</th>
                        <th class="px-5 py-3.5">Office</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Team</th>
                        <th class="px-5 py-3.5">Jadwal</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($assignments as $assignment)
                        <x-assignment.table.row :assignment="$assignment" />
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $assignments->links() }}</div>
@else
    <x-assignment.table.empty />
@endif
