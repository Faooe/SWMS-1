@props(['offices' => collect()])

<form method="GET" action="{{ route('assignments.index') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-[minmax(260px,1.5fr)_repeat(4,minmax(150px,.8fr))_auto]">
        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Cari</label>
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor, judul, atau lokasi..." class="w-full rounded-xl border border-slate-300 py-2.5 pl-9 pr-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
            </div>
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Status</label>
            <select name="status" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <option value="">Semua Status</option>
                @foreach(['Active','Draft','Assigned','In Progress','Pending Review','Needs Revision','Completed','Rejected','Cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status === 'Active' ? 'Aktif' : $status }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Prioritas</label>
            <select name="priority" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <option value="">Semua Prioritas</option>
                @foreach(['Low','Medium','High','Critical'] as $priority)
                    <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Office</label>
            <select name="office" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <option value="">Semua Office</option>
                @foreach($offices as $office)
                    <option value="{{ $office->id }}" @selected((string)request('office') === (string)$office->id)>{{ $office->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Tanggal</label>
            <input type="date" name="date" value="{{ request('date') }}" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Terapkan</button>
            @if(request()->hasAny(['search','status','priority','office','date']))
                <a href="{{ route('assignments.index') }}" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Reset</a>
            @endif
        </div>
    </div>
</form>
