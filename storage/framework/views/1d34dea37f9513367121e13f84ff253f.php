<?php $__env->startSection('title','Assignment'); ?>

<?php $__env->startSection('page-title','Assignment Management'); ?>

<?php $__env->startSection('content'); ?>

<?php if (isset($component)) { $__componentOriginalef07ccf7e4c61bb8985a9e7996366f13 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalef07ccf7e4c61bb8985a9e7996366f13 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.statistics.overview','data' => ['statistics' => $statistics]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.statistics.overview'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['statistics' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statistics)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalef07ccf7e4c61bb8985a9e7996366f13)): ?>
<?php $attributes = $__attributesOriginalef07ccf7e4c61bb8985a9e7996366f13; ?>
<?php unset($__attributesOriginalef07ccf7e4c61bb8985a9e7996366f13); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalef07ccf7e4c61bb8985a9e7996366f13)): ?>
<?php $component = $__componentOriginalef07ccf7e4c61bb8985a9e7996366f13; ?>
<?php unset($__componentOriginalef07ccf7e4c61bb8985a9e7996366f13); ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginal06d8328398cf3e127703b361d0a2c0bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal06d8328398cf3e127703b361d0a2c0bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.actions.create-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.actions.create-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal06d8328398cf3e127703b361d0a2c0bb)): ?>
<?php $attributes = $__attributesOriginal06d8328398cf3e127703b361d0a2c0bb; ?>
<?php unset($__attributesOriginal06d8328398cf3e127703b361d0a2c0bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal06d8328398cf3e127703b361d0a2c0bb)): ?>
<?php $component = $__componentOriginal06d8328398cf3e127703b361d0a2c0bb; ?>
<?php unset($__componentOriginal06d8328398cf3e127703b361d0a2c0bb); ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginalaf71a9211be9dc37809cadbd57aaf505 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaf71a9211be9dc37809cadbd57aaf505 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.filters.search','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.filters.search'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaf71a9211be9dc37809cadbd57aaf505)): ?>
<?php $attributes = $__attributesOriginalaf71a9211be9dc37809cadbd57aaf505; ?>
<?php unset($__attributesOriginalaf71a9211be9dc37809cadbd57aaf505); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaf71a9211be9dc37809cadbd57aaf505)): ?>
<?php $component = $__componentOriginalaf71a9211be9dc37809cadbd57aaf505; ?>
<?php unset($__componentOriginalaf71a9211be9dc37809cadbd57aaf505); ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginald0ed99739c321c9b3aa9fdaefae10b07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0ed99739c321c9b3aa9fdaefae10b07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.table.table','data' => ['assignments' => $assignments]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.table.table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['assignments' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($assignments)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0ed99739c321c9b3aa9fdaefae10b07)): ?>
<?php $attributes = $__attributesOriginald0ed99739c321c9b3aa9fdaefae10b07; ?>
<?php unset($__attributesOriginald0ed99739c321c9b3aa9fdaefae10b07); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0ed99739c321c9b3aa9fdaefae10b07)): ?>
<?php $component = $__componentOriginald0ed99739c321c9b3aa9fdaefae10b07; ?>
<?php unset($__componentOriginald0ed99739c321c9b3aa9fdaefae10b07); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/assignment/index.blade.php ENDPATH**/ ?>