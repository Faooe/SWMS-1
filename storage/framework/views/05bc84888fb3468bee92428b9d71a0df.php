<?php $__env->startSection('title', 'Employee'); ?>

<?php $__env->startSection('page-title', 'Employee Management'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-6">

    <?php if (isset($component)) { $__componentOriginal6bd51efb6db6817ec58ebda8e2bbe03a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6bd51efb6db6817ec58ebda8e2bbe03a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.employee.stats','data' => ['statistics' => $statistics]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('employee.stats'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['statistics' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statistics)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6bd51efb6db6817ec58ebda8e2bbe03a)): ?>
<?php $attributes = $__attributesOriginal6bd51efb6db6817ec58ebda8e2bbe03a; ?>
<?php unset($__attributesOriginal6bd51efb6db6817ec58ebda8e2bbe03a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6bd51efb6db6817ec58ebda8e2bbe03a)): ?>
<?php $component = $__componentOriginal6bd51efb6db6817ec58ebda8e2bbe03a; ?>
<?php unset($__componentOriginal6bd51efb6db6817ec58ebda8e2bbe03a); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal101c3e293aa338a97e40ef5b5dd38a55 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal101c3e293aa338a97e40ef5b5dd38a55 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.employee.toolbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('employee.toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal101c3e293aa338a97e40ef5b5dd38a55)): ?>
<?php $attributes = $__attributesOriginal101c3e293aa338a97e40ef5b5dd38a55; ?>
<?php unset($__attributesOriginal101c3e293aa338a97e40ef5b5dd38a55); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal101c3e293aa338a97e40ef5b5dd38a55)): ?>
<?php $component = $__componentOriginal101c3e293aa338a97e40ef5b5dd38a55; ?>
<?php unset($__componentOriginal101c3e293aa338a97e40ef5b5dd38a55); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal97ffc97e9644c5ba2420b0c0d63083b1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal97ffc97e9644c5ba2420b0c0d63083b1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.employee.table','data' => ['employees' => $employees]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('employee.table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['employees' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employees)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal97ffc97e9644c5ba2420b0c0d63083b1)): ?>
<?php $attributes = $__attributesOriginal97ffc97e9644c5ba2420b0c0d63083b1; ?>
<?php unset($__attributesOriginal97ffc97e9644c5ba2420b0c0d63083b1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal97ffc97e9644c5ba2420b0c0d63083b1)): ?>
<?php $component = $__componentOriginal97ffc97e9644c5ba2420b0c0d63083b1; ?>
<?php unset($__componentOriginal97ffc97e9644c5ba2420b0c0d63083b1); ?>
<?php endif; ?>
    
    <?php if (isset($component)) { $__componentOriginal619092a569db735045a7abd2b12ce157 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal619092a569db735045a7abd2b12ce157 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.employee.filters','data' => ['departments' => $departments]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('employee.filters'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['departments' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($departments)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal619092a569db735045a7abd2b12ce157)): ?>
<?php $attributes = $__attributesOriginal619092a569db735045a7abd2b12ce157; ?>
<?php unset($__attributesOriginal619092a569db735045a7abd2b12ce157); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal619092a569db735045a7abd2b12ce157)): ?>
<?php $component = $__componentOriginal619092a569db735045a7abd2b12ce157; ?>
<?php unset($__componentOriginal619092a569db735045a7abd2b12ce157); ?>
<?php endif; ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/index.blade.php ENDPATH**/ ?>