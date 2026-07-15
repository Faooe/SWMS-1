<?php $__env->startSection('title', 'Company Detail'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal472fede31b564881eaaa7eccfaeac2c8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal472fede31b564881eaaa7eccfaeac2c8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.platform.company-created-modal','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('platform.company-created-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal472fede31b564881eaaa7eccfaeac2c8)): ?>
<?php $attributes = $__attributesOriginal472fede31b564881eaaa7eccfaeac2c8; ?>
<?php unset($__attributesOriginal472fede31b564881eaaa7eccfaeac2c8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal472fede31b564881eaaa7eccfaeac2c8)): ?>
<?php $component = $__componentOriginal472fede31b564881eaaa7eccfaeac2c8; ?>
<?php unset($__componentOriginal472fede31b564881eaaa7eccfaeac2c8); ?>
<?php endif; ?>

<div class="space-y-6">

    
    
    

    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['title' => 'Company Detail','description' => 'Informasi lengkap tenant perusahaan.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Company Detail','description' => 'Informasi lengkap tenant perusahaan.']); ?>

        <div class="flex gap-3">

            <a
                href="<?php echo e(route('platform.companies.edit',$company)); ?>">

                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

                    <i
                        data-lucide="square-pen"
                        class="h-5 w-5">
                    </i>

                    Edit

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

            </a>

            <a
                href="<?php echo e(route('platform.companies.index')); ?>">

                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary']); ?>

                    Back

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

            </a>

        </div>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>

    
    
    

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('generated_password')): ?>

        <div
            class="rounded-2xl border border-amber-200 bg-amber-50 p-6">

            <div class="flex gap-4">

                <i
                    data-lucide="triangle-alert"
                    class="mt-1 h-6 w-6 text-amber-600">
                </i>

                <div>

                    <h3
                        class="font-bold text-amber-700">

                        Password Awal Super Administrator

                    </h3>

                    <p
                        class="mt-2 text-sm text-amber-700">

                        Password ini hanya ditampilkan satu kali.

                    </p>

                    <div
                        class="mt-4 rounded-xl bg-white px-5 py-3 font-mono text-xl font-bold">

                        <?php echo e(session('generated_password')); ?>


                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    
    

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

        <div class="flex flex-col gap-6 md:flex-row md:items-center">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($company->logo): ?>

                <img

                    src="<?php echo e(asset('storage/'.$company->logo)); ?>"

                    class="h-28 w-28 rounded-3xl object-cover">

            <?php else: ?>

                <div

                    class="flex h-28 w-28 items-center justify-center rounded-3xl bg-blue-100 text-4xl font-bold text-blue-600">

                    <?php echo e(strtoupper(substr($company->name,0,1))); ?>


                </div>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="flex-1">

                <h2

                    class="text-3xl font-bold">

                    <?php echo e($company->name); ?>


                </h2>

                <p

                    class="mt-2 text-slate-500">

                    <?php echo e($company->code); ?>


                </p>

                <div

                    class="mt-5 flex flex-wrap gap-3">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($company->is_active): ?>

                        <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'green']); ?>

                            Active

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>

                    <?php else: ?>

                        <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['color' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'red']); ?>

                            Inactive

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'blue']); ?>

                        <?php echo e($company->subscription_plan); ?>


                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>

                </div>

            </div>

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

    
    
    

    <div class="grid gap-6 lg:grid-cols-2">

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

            <h3 class="mb-6 text-lg font-bold">

                Company Information

            </h3>

            <div class="space-y-4">

                <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Company Code','value' => $company->code]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Company Code','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->code)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Email','value' => $company->email]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->email)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Phone','value' => $company->phone]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Phone','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->phone)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Website','value' => $company->website]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Website','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->website)]); ?>
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

            <h3 class="mb-6 text-lg font-bold">

                Address

            </h3>

            <div class="space-y-4">

                <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Address','value' => $company->address]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Address','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->address)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'City','value' => $company->city]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'City','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->city)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Province','value' => $company->province]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Province','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->province)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Postal Code','value' => $company->postal_code]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Postal Code','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->postal_code)]); ?>
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

    </div>

    
    
    

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">

        <?php if (isset($component)) { $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.stat-card','data' => ['title' => 'Employees','value' => $company->employees_count]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Employees','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->employees_count)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $attributes = $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $component = $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.stat-card','data' => ['title' => 'Users','value' => $company->users_count]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Users','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->users_count)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $attributes = $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $component = $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.stat-card','data' => ['title' => 'Offices','value' => $company->offices_count]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Offices','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->offices_count)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $attributes = $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $component = $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.stat-card','data' => ['title' => 'Assignments','value' => $company->assignments_count]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Assignments','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->assignments_count)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $attributes = $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $component = $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>

    </div>
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

        <h3 class="mb-6 text-lg font-bold">

            Super Administrator

        </h3>

        <?php

            $admin = $company->users
                ->firstWhere('role.code','SUPER_ADMIN');

        ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($admin): ?>

            <div class="space-y-4">

                <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Full Name','value' => $admin->employee?->full_name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Full Name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($admin->employee?->full_name)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Username','value' => $admin->username]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Username','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($admin->username)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Email','value' => $admin->email]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($admin->email)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Phone','value' => $admin->employee?->phone]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Phone','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($admin->employee?->phone)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Last Login','value' => $admin->last_login_at]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Last Login','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($admin->last_login_at)]); ?>
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

        <?php else: ?>

            <p class="text-slate-500">

                Super Administrator belum tersedia.

            </p>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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

        <h3 class="mb-6 text-lg font-bold">

            Head Office

        </h3>

        <?php

            $office = $company->offices
                ->firstWhere('is_head_office',true);

        ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office): ?>

            <div class="space-y-4">

                <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Office Name','value' => $office->name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Office Name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($office->name)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Radius','value' => $office->radius.' Meter']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Radius','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($office->radius.' Meter')]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Timezone','value' => $office->timezone]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Timezone','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($office->timezone)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Latitude','value' => $office->latitude]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Latitude','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($office->latitude)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Longitude','value' => $office->longitude]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Longitude','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($office->longitude)]); ?>
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

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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

    <h3 class="mb-6 text-lg font-bold">

        Subscription

    </h3>

    <div class="space-y-4">

        <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Plan','value' => $company->subscription_plan]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Plan','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->subscription_plan)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Start','value' => $company->subscription_start]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Start','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->subscription_start)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Expired','value' => $company->subscription_end]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Expired','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->subscription_end)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Employee Limit','value' => $company->max_employee]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Employee Limit','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->max_employee)]); ?>
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

    <h3 class="mb-6 text-lg font-bold">

        Activity

    </h3>

    <div class="space-y-4">

        <?php if (isset($component)) { $__componentOriginal5dee67fbf7275389ed29c8992f49f2ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dee67fbf7275389ed29c8992f49f2ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Created At','value' => $company->created_at]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Created At','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->created_at)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Updated At','value' => $company->updated_at]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Updated At','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->updated_at)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.detail-item','data' => ['label' => 'Status','value' => $company->is_active ? 'Active' : 'Inactive']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.detail-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Status','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->is_active ? 'Active' : 'Inactive')]); ?>
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

<div class="flex flex-wrap justify-end gap-3">

    <a

        href="<?php echo e(route('platform.companies.edit',$company)); ?>">

        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

            Edit Company

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

    </a>

    <form

        action="<?php echo e(route('platform.companies.destroy',$company)); ?>"

        method="POST"

        onsubmit="return confirm('Delete this company?')">

        <?php echo csrf_field(); ?>

        <?php echo method_field('DELETE'); ?>

        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'danger']); ?>

            Delete

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

    </form>

    <a

        href="<?php echo e(route('platform.companies.index')); ?>">

        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary']); ?>

            Back

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

    </a>

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

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/platform/company/show.blade.php ENDPATH**/ ?>