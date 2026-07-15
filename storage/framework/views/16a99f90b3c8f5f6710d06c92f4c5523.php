<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="mb-5 flex items-center justify-between">

        <div>
            <h3 class="text-lg font-semibold text-slate-800">Team pada Department ini</h3>
            <p class="text-sm text-slate-500">Kelola Team tanpa perlu pindah halaman.</p>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($showForm)): ?>

            <button
                type="button"
                wire:click="createTeam"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Tambah Team
            </button>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($successMessage): ?>

        <div class="mb-4 rounded-2xl bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            <?php echo e($successMessage); ?>

        </div>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errorMessage): ?>

        <div class="mb-4 rounded-2xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            <?php echo e($errorMessage); ?>

        </div>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForm): ?>

        <div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50/50 p-5">

            <h4 class="mb-4 font-semibold text-slate-800">
                <?php echo e($editingId ? 'Edit Team' : 'Tambah Team Baru'); ?>

            </h4>

            <div class="grid gap-4 sm:grid-cols-2">

                <div>

                    <label class="mb-1 block text-sm font-medium text-slate-700">Code</label>

                    <input
                        type="text"
                        wire:model="code"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </div>

                <div>

                    <label class="mb-1 block text-sm font-medium text-slate-700">Name</label>

                    <input
                        type="text"
                        wire:model="name"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </div>

                <div class="sm:col-span-2">

                    <label class="mb-1 block text-sm font-medium text-slate-700">Description</label>

                    <textarea
                        wire:model="description"
                        rows="2"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></textarea>

                </div>

                <label class="flex items-center gap-2 sm:col-span-2">

                    <input
                        type="checkbox"
                        wire:model="is_active"
                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">

                    <span class="text-sm text-slate-700">Active</span>

                </label>

            </div>

            <div class="mt-4 flex justify-end gap-2">

                <button
                    type="button"
                    wire:click="cancelForm"
                    class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </button>

                <button
                    type="button"
                    wire:click="save"
                    wire:loading.attr="disabled"
                    class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60">
                    <span wire:loading.remove wire:target="save">Simpan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>

            </div>

        </div>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="overflow-hidden rounded-2xl border border-slate-200">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Name</th>
                    <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Employee</th>
                    <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Action</th>
                </tr>

            </thead>

            <tbody class="divide-y divide-slate-200 bg-white">

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr wire:key="team-<?php echo e($team->id); ?>" class="hover:bg-slate-50">

                        <td class="px-4 py-3 text-sm font-semibold text-slate-800"><?php echo e($team->code); ?></td>

                        <td class="px-4 py-3 text-sm text-slate-700"><?php echo e($team->name); ?></td>

                        <td class="px-4 py-3 text-center text-sm text-slate-500"><?php echo e($team->employment_histories_count); ?></td>

                        <td class="px-4 py-3 text-center">

                            <button
                                type="button"
                                wire:click="toggleStatus(<?php echo e($team->id); ?>)"
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold',
                                    'bg-green-100 text-green-700' => $team->is_active,
                                    'bg-red-100 text-red-700' => !$team->is_active,
                                ]); ?>">
                                <?php echo e($team->is_active ? 'Active' : 'Inactive'); ?>

                            </button>

                        </td>

                        <td class="px-4 py-3">

                            <div class="flex items-center justify-center gap-2">

                                <button
                                    type="button"
                                    wire:click="editTeam(<?php echo e($team->id); ?>)"
                                    title="Edit Team"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white">
                                    <i data-lucide="pencil" class="h-3.5 w-3.5"></i>
                                </button>

                                <button
                                    type="button"
                                    wire:click="deleteTeam(<?php echo e($team->id); ?>)"
                                    wire:confirm="Yakin ingin menghapus Team ini?"
                                    title="Hapus Team"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white">
                                    <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                </button>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-400">
                            Belum ada Team pada Department ini.
                        </td>
                    </tr>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </tbody>

        </table>

    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/livewire/department/team-manager.blade.php ENDPATH**/ ?>