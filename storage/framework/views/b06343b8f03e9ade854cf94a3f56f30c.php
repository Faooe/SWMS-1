<?php $__env->startSection('title', 'Add Employee'); ?>

<?php $__env->startSection('page-title', 'Add Employee'); ?>

<?php $__env->startSection('content'); ?>

<form

    action="<?php echo e(route('employees.store')); ?>"

    method="POST"

    enctype="multipart/form-data"

    class="space-y-8">

    <?php echo csrf_field(); ?>

    
    <?php if (isset($component)) { $__componentOriginale182a5a3ba7b6ecc64a356c528536839 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale182a5a3ba7b6ecc64a356c528536839 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.employee.forms.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('employee.forms.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale182a5a3ba7b6ecc64a356c528536839)): ?>
<?php $attributes = $__attributesOriginale182a5a3ba7b6ecc64a356c528536839; ?>
<?php unset($__attributesOriginale182a5a3ba7b6ecc64a356c528536839); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale182a5a3ba7b6ecc64a356c528536839)): ?>
<?php $component = $__componentOriginale182a5a3ba7b6ecc64a356c528536839; ?>
<?php unset($__componentOriginale182a5a3ba7b6ecc64a356c528536839); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal3e8f23f7733c71adadbe8e424ee5e491 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3e8f23f7733c71adadbe8e424ee5e491 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.employee.forms.personal-information','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('employee.forms.personal-information'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3e8f23f7733c71adadbe8e424ee5e491)): ?>
<?php $attributes = $__attributesOriginal3e8f23f7733c71adadbe8e424ee5e491; ?>
<?php unset($__attributesOriginal3e8f23f7733c71adadbe8e424ee5e491); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3e8f23f7733c71adadbe8e424ee5e491)): ?>
<?php $component = $__componentOriginal3e8f23f7733c71adadbe8e424ee5e491; ?>
<?php unset($__componentOriginal3e8f23f7733c71adadbe8e424ee5e491); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal4fd6dec86c8c4aa9f528e70b81ce393f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4fd6dec86c8c4aa9f528e70b81ce393f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.employee.forms.employment-information','data' => ['offices' => $offices,'departments' => $departments,'positions' => $positions,'teams' => $teams,'employees' => $employees]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('employee.forms.employment-information'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['offices' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($offices),'departments' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($departments),'positions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($positions),'teams' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($teams),'employees' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employees)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4fd6dec86c8c4aa9f528e70b81ce393f)): ?>
<?php $attributes = $__attributesOriginal4fd6dec86c8c4aa9f528e70b81ce393f; ?>
<?php unset($__attributesOriginal4fd6dec86c8c4aa9f528e70b81ce393f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4fd6dec86c8c4aa9f528e70b81ce393f)): ?>
<?php $component = $__componentOriginal4fd6dec86c8c4aa9f528e70b81ce393f; ?>
<?php unset($__componentOriginal4fd6dec86c8c4aa9f528e70b81ce393f); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginalf0992c21161fcc3d08a7d9b07f962271 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf0992c21161fcc3d08a7d9b07f962271 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.employee.forms.account-information','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('employee.forms.account-information'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf0992c21161fcc3d08a7d9b07f962271)): ?>
<?php $attributes = $__attributesOriginalf0992c21161fcc3d08a7d9b07f962271; ?>
<?php unset($__attributesOriginalf0992c21161fcc3d08a7d9b07f962271); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf0992c21161fcc3d08a7d9b07f962271)): ?>
<?php $component = $__componentOriginalf0992c21161fcc3d08a7d9b07f962271; ?>
<?php unset($__componentOriginalf0992c21161fcc3d08a7d9b07f962271); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginalf1cf3f769960d6355e1f653398d3d306 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf1cf3f769960d6355e1f653398d3d306 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.employee.forms.photo-upload','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('employee.forms.photo-upload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf1cf3f769960d6355e1f653398d3d306)): ?>
<?php $attributes = $__attributesOriginalf1cf3f769960d6355e1f653398d3d306; ?>
<?php unset($__attributesOriginalf1cf3f769960d6355e1f653398d3d306); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf1cf3f769960d6355e1f653398d3d306)): ?>
<?php $component = $__componentOriginalf1cf3f769960d6355e1f653398d3d306; ?>
<?php unset($__componentOriginalf1cf3f769960d6355e1f653398d3d306); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal2adea3ce74d12e31812197444b7b1403 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2adea3ce74d12e31812197444b7b1403 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.employee.forms.action-buttons','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('employee.forms.action-buttons'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2adea3ce74d12e31812197444b7b1403)): ?>
<?php $attributes = $__attributesOriginal2adea3ce74d12e31812197444b7b1403; ?>
<?php unset($__attributesOriginal2adea3ce74d12e31812197444b7b1403); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2adea3ce74d12e31812197444b7b1403)): ?>
<?php $component = $__componentOriginal2adea3ce74d12e31812197444b7b1403; ?>
<?php unset($__componentOriginal2adea3ce74d12e31812197444b7b1403); ?>
<?php endif; ?>

</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/create.blade.php ENDPATH**/ ?>