<?php $__env->startSection('title','Employee Detail'); ?>

<?php $__env->startSection('page-title','Employee Detail'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-8">

    
    <div
        class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

        <div
            class="flex flex-col justify-between gap-6 lg:flex-row lg:items-center">

            <div class="flex items-center gap-6">

                <div
                    <x-ui.avatar:employee="$employee"size="24"/>
                </div>

                <div>

                    <h1
                        class="text-3xl font-bold text-slate-800">

                        <?php echo e($employee->full_name); ?>


                    </h1>

                    <p class="mt-2 text-slate-500">

                        Employee Number :
                        <strong><?php echo e($employee->employee_number); ?></strong>

                    </p>

                    <div class="mt-4">

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->is_active): ?>

                            <span
                                class="rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">

                                Active

                            </span>

                        <?php else: ?>

                            <span
                                class="rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-700">

                                Inactive

                            </span>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    </div>

                </div>

            </div>

            <div class="flex gap-3">

                <a
                    href="<?php echo e(route('employees.edit',$employee)); ?>"
                    class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700">

                    Edit Employee

                </a>

                <a
                    href="<?php echo e(route('employees.index')); ?>"
                    class="rounded-xl border border-slate-300 px-6 py-3 font-semibold hover:bg-slate-100">

                    Back

                </a>

            </div>

        </div>

    </div>

    
    <div
        class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

        <h2
            class="mb-8 text-xl font-bold">

            Personal Information

        </h2>

        <div class="grid gap-6 md:grid-cols-2">

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Employee Number','value' => $employee->employee_number]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Employee Number','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->employee_number)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Full Name','value' => $employee->full_name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Full Name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->full_name)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Email','value' => $employee->email]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->email)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Phone','value' => $employee->phone]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Phone','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->phone)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Gender','value' => $employee->gender]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Gender','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->gender)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Birth Place','value' => $employee->birth_place]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Birth Place','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->birth_place)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Birth Date','value' => optional($employee->birth_date)->format('d M Y')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Birth Date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(optional($employee->birth_date)->format('d M Y'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Identity Number','value' => $employee->identity_number]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Identity Number','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->identity_number)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Marital Status','value' => $employee->marital_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Marital Status','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->marital_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Emergency Contact','value' => $employee->emergency_contact_name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Emergency Contact','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->emergency_contact_name)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Emergency Phone','value' => $employee->emergency_contact_phone]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Emergency Phone','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->emergency_contact_phone)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

        </div>

        <div class="mt-6">

            <label
                class="mb-2 block text-sm font-semibold text-slate-500">

                Address

            </label>

            <div
                class="rounded-2xl border border-slate-200 bg-slate-50 p-4">

                <?php echo e($employee->address ?: '-'); ?>


            </div>

        </div>

    </div>

    
    <div
        class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

        <h2
            class="mb-8 text-xl font-bold">

            Employment Information

        </h2>

        <div class="grid gap-6 md:grid-cols-2">

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Office','value' => $employee->currentEmployment?->office?->name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Office','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->currentEmployment?->office?->name)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Department','value' => $employee->currentEmployment?->department?->name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Department','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->currentEmployment?->department?->name)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Position','value' => $employee->currentEmployment?->position?->name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Position','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->currentEmployment?->position?->name)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Team','value' => $employee->currentEmployment?->team?->name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Team','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->currentEmployment?->team?->name)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Supervisor','value' => $employee->currentEmployment?->supervisor?->full_name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Supervisor','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->currentEmployment?->supervisor?->full_name)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Employment Type','value' => $employee->currentEmployment?->employment_type]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Employment Type','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->currentEmployment?->employment_type)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Employment Status','value' => $employee->currentEmployment?->employment_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Employment Status','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->currentEmployment?->employment_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Start Date','value' => optional($employee->currentEmployment?->start_date)->format('d M Y')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Start Date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(optional($employee->currentEmployment?->start_date)->format('d M Y'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'End Date','value' => optional($employee->currentEmployment?->end_date)->format('d M Y')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'End Date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(optional($employee->currentEmployment?->end_date)->format('d M Y'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Current Employment','value' => $employee->currentEmployment?->is_current ? 'Yes' : 'No']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Current Employment','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee->currentEmployment?->is_current ? 'Yes' : 'No')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $attributes = $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee)): ?>
<?php $component = $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee; ?>
<?php unset($__componentOriginal5dee67fbf7275389ed29c8992f49f2ee); ?>
<?php endif; ?>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/show.blade.php ENDPATH**/ ?>