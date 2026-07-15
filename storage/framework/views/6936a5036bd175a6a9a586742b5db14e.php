<?php $__env->startSection('title','Assignment Detail'); ?>

<?php $__env->startSection('page-title','Assignment Detail'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-8">

    <?php if (isset($component)) { $__componentOriginalaa5f4b1356b3cc9e3f884430856bbf34 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaa5f4b1356b3cc9e3f884430856bbf34 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.show.header','data' => ['assignment' => $assignment]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.show.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['assignment' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($assignment)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaa5f4b1356b3cc9e3f884430856bbf34)): ?>
<?php $attributes = $__attributesOriginalaa5f4b1356b3cc9e3f884430856bbf34; ?>
<?php unset($__attributesOriginalaa5f4b1356b3cc9e3f884430856bbf34); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaa5f4b1356b3cc9e3f884430856bbf34)): ?>
<?php $component = $__componentOriginalaa5f4b1356b3cc9e3f884430856bbf34; ?>
<?php unset($__componentOriginalaa5f4b1356b3cc9e3f884430856bbf34); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalc91711f9ea165d51303e757fd862e7e3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc91711f9ea165d51303e757fd862e7e3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.show.information','data' => ['assignment' => $assignment]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.show.information'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['assignment' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($assignment)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc91711f9ea165d51303e757fd862e7e3)): ?>
<?php $attributes = $__attributesOriginalc91711f9ea165d51303e757fd862e7e3; ?>
<?php unset($__attributesOriginalc91711f9ea165d51303e757fd862e7e3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc91711f9ea165d51303e757fd862e7e3)): ?>
<?php $component = $__componentOriginalc91711f9ea165d51303e757fd862e7e3; ?>
<?php unset($__componentOriginalc91711f9ea165d51303e757fd862e7e3); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalf64bde8992a8a0ca9ebb9a5cfa8387e6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf64bde8992a8a0ca9ebb9a5cfa8387e6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.show.location','data' => ['assignment' => $assignment]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.show.location'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['assignment' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($assignment)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf64bde8992a8a0ca9ebb9a5cfa8387e6)): ?>
<?php $attributes = $__attributesOriginalf64bde8992a8a0ca9ebb9a5cfa8387e6; ?>
<?php unset($__attributesOriginalf64bde8992a8a0ca9ebb9a5cfa8387e6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf64bde8992a8a0ca9ebb9a5cfa8387e6)): ?>
<?php $component = $__componentOriginalf64bde8992a8a0ca9ebb9a5cfa8387e6; ?>
<?php unset($__componentOriginalf64bde8992a8a0ca9ebb9a5cfa8387e6); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalf1801ccd46480b76e65bb1c0ab182690 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf1801ccd46480b76e65bb1c0ab182690 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.show.employees','data' => ['assignment' => $assignment]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.show.employees'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['assignment' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($assignment)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf1801ccd46480b76e65bb1c0ab182690)): ?>
<?php $attributes = $__attributesOriginalf1801ccd46480b76e65bb1c0ab182690; ?>
<?php unset($__attributesOriginalf1801ccd46480b76e65bb1c0ab182690); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf1801ccd46480b76e65bb1c0ab182690)): ?>
<?php $component = $__componentOriginalf1801ccd46480b76e65bb1c0ab182690; ?>
<?php unset($__componentOriginalf1801ccd46480b76e65bb1c0ab182690); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalc298706c79bc069f93ef9273a0aebc46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc298706c79bc069f93ef9273a0aebc46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.show.timeline','data' => ['assignment' => $assignment]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.show.timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['assignment' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($assignment)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc298706c79bc069f93ef9273a0aebc46)): ?>
<?php $attributes = $__attributesOriginalc298706c79bc069f93ef9273a0aebc46; ?>
<?php unset($__attributesOriginalc298706c79bc069f93ef9273a0aebc46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc298706c79bc069f93ef9273a0aebc46)): ?>
<?php $component = $__componentOriginalc298706c79bc069f93ef9273a0aebc46; ?>
<?php unset($__componentOriginalc298706c79bc069f93ef9273a0aebc46); ?>
<?php endif; ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/assignment/show.blade.php ENDPATH**/ ?>