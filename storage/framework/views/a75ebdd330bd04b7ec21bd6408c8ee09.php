<?php $__env->startSection('title','Create Assignment'); ?>

<?php $__env->startSection('page-title','Create Assignment'); ?>

<?php $__env->startSection('content'); ?>

<form
    action="<?php echo e(route('assignments.store')); ?>"
    method="POST"
    class="space-y-8">

    <?php echo csrf_field(); ?>

    <?php if (isset($component)) { $__componentOriginal8c4c5eb4437884a50801433fe0087e2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c4c5eb4437884a50801433fe0087e2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.forms.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.forms.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c4c5eb4437884a50801433fe0087e2f)): ?>
<?php $attributes = $__attributesOriginal8c4c5eb4437884a50801433fe0087e2f; ?>
<?php unset($__attributesOriginal8c4c5eb4437884a50801433fe0087e2f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c4c5eb4437884a50801433fe0087e2f)): ?>
<?php $component = $__componentOriginal8c4c5eb4437884a50801433fe0087e2f; ?>
<?php unset($__componentOriginal8c4c5eb4437884a50801433fe0087e2f); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal472b5569d568adb81d4b16473074bf52 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal472b5569d568adb81d4b16473074bf52 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.forms.assignment-information','data' => ['offices' => $offices,'priorities' => $priorities,'types' => $types,'statuses' => $statuses]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.forms.assignment-information'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['offices' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($offices),'priorities' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($priorities),'types' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($types),'statuses' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statuses)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal472b5569d568adb81d4b16473074bf52)): ?>
<?php $attributes = $__attributesOriginal472b5569d568adb81d4b16473074bf52; ?>
<?php unset($__attributesOriginal472b5569d568adb81d4b16473074bf52); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal472b5569d568adb81d4b16473074bf52)): ?>
<?php $component = $__componentOriginal472b5569d568adb81d4b16473074bf52; ?>
<?php unset($__componentOriginal472b5569d568adb81d4b16473074bf52); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal3404d529aad3b94f7d4f42def9c99b58 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3404d529aad3b94f7d4f42def9c99b58 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.forms.location-information','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.forms.location-information'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3404d529aad3b94f7d4f42def9c99b58)): ?>
<?php $attributes = $__attributesOriginal3404d529aad3b94f7d4f42def9c99b58; ?>
<?php unset($__attributesOriginal3404d529aad3b94f7d4f42def9c99b58); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3404d529aad3b94f7d4f42def9c99b58)): ?>
<?php $component = $__componentOriginal3404d529aad3b94f7d4f42def9c99b58; ?>
<?php unset($__componentOriginal3404d529aad3b94f7d4f42def9c99b58); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal6ec9f0801fac6b21a25670d070ec6493 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ec9f0801fac6b21a25670d070ec6493 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.forms.employee-information','data' => ['employees' => $employees]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.forms.employee-information'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['employees' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employees)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ec9f0801fac6b21a25670d070ec6493)): ?>
<?php $attributes = $__attributesOriginal6ec9f0801fac6b21a25670d070ec6493; ?>
<?php unset($__attributesOriginal6ec9f0801fac6b21a25670d070ec6493); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ec9f0801fac6b21a25670d070ec6493)): ?>
<?php $component = $__componentOriginal6ec9f0801fac6b21a25670d070ec6493; ?>
<?php unset($__componentOriginal6ec9f0801fac6b21a25670d070ec6493); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal8af9c93b380f1b8c8ba97cf247d2e827 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8af9c93b380f1b8c8ba97cf247d2e827 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.forms.action-buttons','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.forms.action-buttons'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8af9c93b380f1b8c8ba97cf247d2e827)): ?>
<?php $attributes = $__attributesOriginal8af9c93b380f1b8c8ba97cf247d2e827; ?>
<?php unset($__attributesOriginal8af9c93b380f1b8c8ba97cf247d2e827); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8af9c93b380f1b8c8ba97cf247d2e827)): ?>
<?php $component = $__componentOriginal8af9c93b380f1b8c8ba97cf247d2e827; ?>
<?php unset($__componentOriginal8af9c93b380f1b8c8ba97cf247d2e827); ?>
<?php endif; ?>

</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/assignment/create.blade.php ENDPATH**/ ?>