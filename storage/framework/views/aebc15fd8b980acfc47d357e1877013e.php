<?php $__env->startSection('title', 'My Profile'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-8">

    
    <div>

        <h1 class="text-3xl font-bold text-slate-800">

            My Profile

        </h1>

        <p class="mt-2 text-slate-500">

            Kelola informasi akun Platform dan ubah password Anda.

        </p>

    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>

        <div
            class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-700">

            <?php echo e(session('success')); ?>


        </div>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="grid gap-8 xl:grid-cols-2">

        
        
        

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

            <div class="mb-8">

                <h2 class="text-xl font-bold">

                    Account Information

                </h2>

                <p class="mt-2 text-sm text-slate-500">

                    Informasi akun Platform Smart Workforce Management System.

                </p>

            </div>

            <div class="flex flex-col items-center">

                <div
                    class="flex h-24 w-24 items-center justify-center rounded-full bg-blue-600 text-4xl font-bold text-white">

                    <?php echo e(strtoupper(substr($user->username,0,1))); ?>


                </div>

                <h2 class="mt-5 text-2xl font-bold">

                    <?php echo e($user->username); ?>


                </h2>

                <p class="mt-1 text-slate-500">

                    <?php echo e($user->email); ?>


                </p>

            </div>

            <div class="mt-10 space-y-5">

                <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Username','value' => $user->username]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Username','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->username)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Email','value' => $user->email]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->email)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Role','value' => $user->role->name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Role','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->role->name)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Status','value' => $user->is_active ? 'Active' : 'Inactive']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Status','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->is_active ? 'Active' : 'Inactive')]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Last Login','value' => $user->last_login_at
                        ? $user->last_login_at->format('d M Y H:i')
                        : '-']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Last Login','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->last_login_at
                        ? $user->last_login_at->format('d M Y H:i')
                        : '-')]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Created At','value' => $user->created_at->format('d M Y H:i')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Created At','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->created_at->format('d M Y H:i'))]); ?>
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

            <div class="mb-8">

                <h2 class="text-xl font-bold">

                    Change Password

                </h2>

                <p class="mt-2 text-sm text-slate-500">

                    Gunakan password yang kuat agar akun Platform tetap aman.

                </p>

            </div>

            <form

                method="POST"

                action="<?php echo e(route('platform.profile.update')); ?>"

                class="space-y-6">

                <?php echo csrf_field(); ?>

                <?php echo method_field('PUT'); ?>

                <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Current Password','name' => 'current_password','type' => 'password','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Current Password','name' => 'current_password','type' => 'password','required' => true]); ?>
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

                <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'New Password','name' => 'password','type' => 'password','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'New Password','name' => 'password','type' => 'password','required' => true]); ?>
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

                <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Confirm Password','name' => 'password_confirmation','type' => 'password','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Confirm Password','name' => 'password_confirmation','type' => 'password','required' => true]); ?>
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

                <div class="pt-2">

                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit']); ?>

                        <i
                            data-lucide="lock-keyhole"
                            class="h-5 w-5">
                        </i>

                        Update Password

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>

                </div>

            </form>

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

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/platform/profile/index.blade.php ENDPATH**/ ?>