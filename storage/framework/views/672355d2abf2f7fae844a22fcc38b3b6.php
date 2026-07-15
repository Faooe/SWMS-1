<form
    method="GET"
    class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

    <div
        class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-5">

        
        <div>

            <label
                class="mb-2 block text-sm font-medium text-slate-600">

                Search Employee

            </label>

            <input
                type="text"
                name="search"
                value="<?php echo e(request('search')); ?>"
                placeholder="Employee Name..."
                class="w-full rounded-xl border-slate-300">

        </div>

        
        <div>

            <label
                class="mb-2 block text-sm font-medium text-slate-600">

                Office

            </label>

            <select
                name="office"
                class="w-full rounded-xl border-slate-300">

                <option value="">

                    All Office

                </option>

            </select>

        </div>

        
        <div>

            <label
                class="mb-2 block text-sm font-medium text-slate-600">

                Status

            </label>

            <select
                name="status"
                class="w-full rounded-xl border-slate-300">

                <option value="">

                    All Status

                </option>

                <option value="Present">

                    Present

                </option>

                <option value="Late">

                    Late

                </option>

                <option value="Leave">

                    Leave

                </option>

                <option value="Permission">

                    Permission

                </option>

                <option value="Absent">

                    Absent

                </option>

            </select>

        </div>

        
        <div>

            <label
                class="mb-2 block text-sm font-medium text-slate-600">

                Attendance Date

            </label>

            <input
                type="date"
                name="date"
                value="<?php echo e(request('date')); ?>"
                class="w-full rounded-xl border-slate-300">

        </div>

        
        <div
            class="flex items-end gap-3">

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700">

                Filter

            </button>

            <a
                href="<?php echo e(route('attendance.index')); ?>"
                class="rounded-xl border border-slate-300 px-5 py-3 font-semibold">

                Reset

            </a>

        </div>

    </div>

</form><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/attendance/partials/filters.blade.php ENDPATH**/ ?>