<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="mb-5 flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-50 text-violet-600">
            <i data-lucide="users-round" class="h-5 w-5"></i>
        </div>
        <div>
            <h2 class="font-bold text-slate-900">Tim Assignment</h2>
            <p class="text-xs text-slate-500">{{ $assignment->employees->count() }} employee ditugaskan.</p>
        </div>
    </div>

    <div class="space-y-3">
        @foreach($assignment->employees as $employee)
            @php
                $pivot = $employee->pivot;
                $displayStatus = match($pivot->review_status) {
                    'Approved' => 'Completed',
                    'Pending Review' => 'Pending Review',
                    'Needs Revision' => 'Needs Revision',
                    'Not Worked', 'Expired' => 'Not Worked',
                    default => $pivot->status,
                };

                $statusClass = match($displayStatus) {
                    'Assigned' => 'bg-blue-100 text-blue-700',
                    'Accepted' => 'bg-indigo-100 text-indigo-700',
                    'In Progress' => 'bg-amber-100 text-amber-700',
                    'Pending Review' => 'bg-violet-100 text-violet-700',
                    'Needs Revision' => 'bg-rose-100 text-rose-700',
                    'Completed' => 'bg-emerald-100 text-emerald-700',
                    'Rejected' => 'bg-red-100 text-red-700',
                    'Not Worked' => 'bg-slate-200 text-slate-700',
                    default => 'bg-slate-100 text-slate-600',
                };
                $isMe = (int)$employee->id === (int)auth()->user()->employee_id;
            @endphp

            <div class="rounded-2xl border {{ $isMe ? 'border-blue-200 bg-blue-50/40' : 'border-slate-100 bg-slate-50/50' }} p-3">
                <div class="flex items-center gap-3">
                    @if($employee->photo)
                        <img src="{{ secure_file_url($employee->photo) }}" alt="{{ $employee->full_name }}" class="h-10 w-10 rounded-xl object-cover ring-2 ring-white">
                    @else
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white font-black text-blue-600 shadow-sm">
                            {{ strtoupper(substr($employee->full_name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <p class="truncate text-sm font-bold text-slate-800">{{ $employee->full_name }}</p>
                            @if($isMe)
                                <span class="rounded-full bg-blue-600 px-2 py-0.5 text-[10px] font-bold text-white">Kamu</span>
                            @endif
                        </div>
                        <p class="truncate text-xs text-slate-500">{{ $employee->currentEmployment?->position?->name ?? '-' }}</p>
                    </div>
                </div>

                <div class="mt-3 flex justify-end">
                    <span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $statusClass }}">{{ $displayStatus }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
