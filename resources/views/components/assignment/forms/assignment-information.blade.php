@props([
    'assignment' => null,
    'offices',
    'priorities',
    'types',
    'statuses',
])

<x-assignment.section-card
    title="Assignment Information"
    description="Basic information about the assignment."
    icon="clipboard-list">

    <div class="grid gap-6 md:grid-cols-2">

        {{-- Title --}}
        <x-ui.input
            name="title"
            label="Assignment Title"
            :value="$assignment?->title"
            required />

        {{-- Office --}}
        <x-ui.select
            name="office_id"
            label="Office"
            :options="$offices"
            :selected="$assignment?->office_id"
            placeholder="Select Office"
            required />

        {{-- Priority --}}
        <div>

            <label
                for="priority"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Priority

                <span class="text-red-500">*</span>

            </label>

            <select
                id="priority"
                name="priority"
                required
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                <option value="">
                    Select Priority
                </option>

                @foreach($priorities as $priority)

                    <option
                        value="{{ $priority }}"
                        @selected(old('priority',$assignment?->priority)==$priority)>

                        {{ $priority }}

                    </option>

                @endforeach

            </select>

        </div>

        {{-- Assignment Type --}}
        <div>

            <label
                for="assignment_type"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Assignment Type

                <span class="text-red-500">*</span>

            </label>

            <select
                id="assignment_type"
                name="assignment_type"
                required
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                <option value="">
                    Select Type
                </option>

                @foreach($types as $type)

                    <option
                        value="{{ $type }}"
                        @selected(old('assignment_type',$assignment?->assignment_type)==$type)>

                        {{ $type }}

                    </option>

                @endforeach

            </select>

        </div>

        {{-- Status --}}
        <div>

            <label
                for="status"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Status

                @unless($assignment && in_array($assignment->status, ['In Progress', 'Completed']))
                    <span class="text-red-500">*</span>
                @endunless

            </label>

            @if($assignment && in_array($assignment->status, ['In Progress', 'Completed']))

                {{-- Status otomatis: tidak bisa diubah manual --}}
                <div class="flex w-full items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">

                    <span class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
                        {{ $assignment->status }}
                    </span>

                    <span class="text-xs text-slate-400">
                        (otomatis, mengikuti aksi employee)
                    </span>

                </div>

                <input type="hidden" name="status" value="{{ $assignment->status }}">

            @else

                <select
                    id="status"
                    name="status"
                    required
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                    @foreach($statuses as $status)

                        <option
                            value="{{ $status }}"
                            @selected(old('status',$assignment?->status ?? 'Draft')==$status)>

                            {{ $status }}

                        </option>

                    @endforeach

                </select>

                <p class="mt-2 text-xs text-slate-400">
                    Pilih <strong>Draft</strong> untuk menjadwalkan tugas di tanggal mendatang (akan otomatis menjadi <strong>Assigned</strong> saat jadwalnya tiba). Status <strong>In Progress</strong> dan <strong>Completed</strong> akan berubah otomatis mengikuti aksi employee.
                </p>

            @endif

        </div>

        {{-- Start --}}
        <x-ui.input
            name="start_datetime"
            type="datetime-local"
            label="Start Date & Time"
            :value="$assignment?->start_datetime?->format('Y-m-d\TH:i')"
            required />

        {{-- End --}}
        <x-ui.input
            name="end_datetime"
            type="datetime-local"
            label="End Date & Time"
            :value="$assignment?->end_datetime?->format('Y-m-d\TH:i')"
            required />

    </div>

    <div class="mt-6">

        <label
            for="description"
            class="mb-2 block text-sm font-semibold text-slate-700">

            Description

        </label>

        <textarea
            id="description"
            name="description"
            rows="5"
            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ old('description',$assignment?->description) }}</textarea>

    </div>

</x-assignment.section-card>