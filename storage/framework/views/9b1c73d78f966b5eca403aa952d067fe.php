<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'employee' => null,
    'offices',
    'departments',
    'positions',
    'teams',
    'employees',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'employee' => null,
    'offices',
    'departments',
    'positions',
    'teams',
    'employees',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if (isset($component)) { $__componentOriginal70be86c470f0bdd09cb96a796f41a3f3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal70be86c470f0bdd09cb96a796f41a3f3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.employee.section-card','data' => ['title' => 'Employment Information','description' => 'Current employment information.','icon' => 'briefcase']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('employee.section-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Employment Information','description' => 'Current employment information.','icon' => 'briefcase']); ?>

    <div class="grid gap-6 md:grid-cols-2">

        
        <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.select','data' => ['name' => 'office_id','label' => 'Office','options' => $offices,'selected' => $employee?->currentEmployment?->office_id,'placeholder' => 'Select Office','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'office_id','label' => 'Office','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($offices),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee?->currentEmployment?->office_id),'placeholder' => 'Select Office','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

        
        <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.select','data' => ['name' => 'department_id','label' => 'Department','options' => $departments,'selected' => $employee?->currentEmployment?->department_id,'placeholder' => 'Select Department','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'department_id','label' => 'Department','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($departments),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee?->currentEmployment?->department_id),'placeholder' => 'Select Department','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

        
        <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.select','data' => ['name' => 'position_id','label' => 'Position','options' => $positions,'selected' => $employee?->currentEmployment?->position_id,'placeholder' => 'Select Position','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'position_id','label' => 'Position','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($positions),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee?->currentEmployment?->position_id),'placeholder' => 'Select Position','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

        
        <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.select','data' => ['name' => 'team_id','label' => 'Team','options' => $teams,'selected' => $employee?->currentEmployment?->team_id,'placeholder' => 'Select Team']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'team_id','label' => 'Team','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($teams),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee?->currentEmployment?->team_id),'placeholder' => 'Select Team']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

        
        <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.select','data' => ['name' => 'supervisor_id','label' => 'Supervisor','options' => $employees,'selected' => $employee?->currentEmployment?->supervisor_id,'placeholder' => 'Select Supervisor']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'supervisor_id','label' => 'Supervisor','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employees),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee?->currentEmployment?->supervisor_id),'placeholder' => 'Select Supervisor']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

        
        <div>

            <label
                for="employment_type"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Employment Type

                <span class="text-red-500">*</span>

            </label>

            <select

                id="employment_type"

                name="employment_type"

                required

                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"

            >

                <option value="">

                    Select Type

                </option>

                <option
                    value="Permanent"
                    <?php if(old('employment_type',$employee?->currentEmployment?->employment_type)=='Permanent'): echo 'selected'; endif; ?>>

                    Permanent

                </option>

                <option
                    value="Contract"
                    <?php if(old('employment_type',$employee?->currentEmployment?->employment_type)=='Contract'): echo 'selected'; endif; ?>>

                    Contract

                </option>

                <option
                    value="Internship"
                    <?php if(old('employment_type',$employee?->currentEmployment?->employment_type)=='Internship'): echo 'selected'; endif; ?>>

                    Internship

                </option>

            </select>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['employment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                <p class="mt-2 text-sm text-red-500">

                    <?php echo e($message); ?>


                </p>

            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

        
        <div>

            <label
                for="employment_status"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Employment Status

            </label>

            <select

                id="employment_status"

                name="employment_status"

                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"

            >

                <option value="">

                    Select Status

                </option>

                <option
                    value="Active"
                    <?php if(old('employment_status',$employee?->currentEmployment?->employment_status)=='Active'): echo 'selected'; endif; ?>>

                    Active

                </option>

                <option
                    value="Probation"
                    <?php if(old('employment_status',$employee?->currentEmployment?->employment_status)=='Probation'): echo 'selected'; endif; ?>>

                    Probation

                </option>

                <option
                    value="Resigned"
                    <?php if(old('employment_status',$employee?->currentEmployment?->employment_status)=='Resigned'): echo 'selected'; endif; ?>>

                    Resigned

                </option>

            </select>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['employment_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                <p class="mt-2 text-sm text-red-500">

                    <?php echo e($message); ?>


                </p>

            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

        
        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['name' => 'start_date','type' => 'date','label' => 'Start Date','value' => $employee?->currentEmployment?->start_date?->format('Y-m-d'),'required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'start_date','type' => 'date','label' => 'Start Date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee?->currentEmployment?->start_date?->format('Y-m-d')),'required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

        
        <div>

            <label
                for="is_active"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Employee Status

            </label>

            <select

                id="is_active"

                name="is_active"

                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm
                    focus:border-blue-500
                    focus:ring-4
                    focus:ring-blue-100">

                <option
                    value="1"
                    <?php if(old('is_active', $employee?->is_active) == true): echo 'selected'; endif; ?>>

                    Active

                </option>

                <option
                    value="0"
                    <?php if(old('is_active', $employee?->is_active) == false): echo 'selected'; endif; ?>>

                    Inactive

                </option>

            </select>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                <p class="mt-2 text-sm text-red-500">

                    <?php echo e($message); ?>


                </p>

            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal70be86c470f0bdd09cb96a796f41a3f3)): ?>
<?php $attributes = $__attributesOriginal70be86c470f0bdd09cb96a796f41a3f3; ?>
<?php unset($__attributesOriginal70be86c470f0bdd09cb96a796f41a3f3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal70be86c470f0bdd09cb96a796f41a3f3)): ?>
<?php $component = $__componentOriginal70be86c470f0bdd09cb96a796f41a3f3; ?>
<?php unset($__componentOriginal70be86c470f0bdd09cb96a796f41a3f3); ?>
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/employee/forms/employment-information.blade.php ENDPATH**/ ?>