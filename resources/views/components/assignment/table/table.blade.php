@props([
    'assignments'
])

@if($assignments->count())

<div class="mt-8 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

    <div class="max-h-[520px] overflow-y-auto overflow-x-auto">

        <table class="min-w-full">

            <thead class="sticky top-0 z-10 bg-slate-100">

                <tr>

                    <th class="px-6 py-4 text-left">Number</th>

                    <th class="px-6 py-4 text-left">Assignment</th>

                    <th class="px-6 py-4 text-left">Office</th>

                    <th class="px-6 py-4 text-left">Priority</th>

                    <th class="px-6 py-4 text-left">Status</th>

                    <th class="px-6 py-4 text-left">Employees</th>

                    <th class="px-6 py-4 text-left">Schedule</th>

                    <th class="px-6 py-4 text-center">Action</th>

                </tr>

            </thead>

            <tbody>

                @foreach($assignments as $assignment)

                    <x-assignment.table.row
                        :assignment="$assignment"/>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

<div class="mt-6">

    {{ $assignments->links() }}

</div>

@else

<x-assignment.table.empty/>

@endif