<?php

$user = auth()->user();

$displayName = $user->employee?->full_name
    ?? $user->username
    ?? 'Platform Administrator';

$displayEmail = $user->email
    ?? '-';

?>

<header
    class="sticky top-0 z-30 flex h-20 items-center justify-between gap-3 border-b border-slate-200 bg-white px-4 lg:px-8">

    
    <div class="flex min-w-0 flex-1 items-center gap-3 lg:gap-5">

        
        <button
            id="sidebar-toggle"
            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white transition hover:bg-slate-100 lg:hidden">

            <i
                id="sidebar-toggle-icon"
                data-lucide="panel-left-close"
                class="h-5 w-5">
            </i>

        </button>

        <div class="min-w-0">

            <p class="truncate text-sm font-medium text-slate-400">

                Dashboard

                <?php if (! empty(trim($__env->yieldContent('page-title')))): ?>

                    /

                    <?php echo $__env->yieldContent('page-title'); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </p>

            <h1 class="truncate text-xl font-bold tracking-tight text-slate-800 lg:text-2xl">

                <?php echo $__env->yieldContent('page-title','Dashboard'); ?>

            </h1>

        </div>

    </div>

    
    <div class="flex shrink-0 items-center gap-2 lg:gap-3">

        
        <div class="relative hidden lg:block">

            <i
                data-lucide="search"
                class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400">
            </i>

            <input
                type="text"
                placeholder="Search..."
                class="w-80 rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">

        </div>

        
        <button
            class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white transition hover:bg-slate-100">

            <span
                class="absolute right-3 top-3 h-2.5 w-2.5 rounded-full border-2 border-white bg-red-500">
            </span>

            <i
                data-lucide="bell"
                class="h-5 w-5">
            </i>

        </button>

        
        <button
            class="hidden h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white transition hover:bg-slate-100 sm:flex">

            <i
                data-lucide="settings-2"
                class="h-5 w-5">
            </i>

        </button>

        
        <div
            x-data="{ open:false }"
            class="relative shrink-0">

            <button

                @click="open=!open"

                class="flex items-center gap-2 rounded-2xl px-1 py-1 transition hover:bg-slate-100 lg:gap-3 lg:px-2 lg:py-2">

                <?php if (isset($component)) { $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.avatar','data' => ['employee' => $user->employee,'size' => '10']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['employee' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->employee),'size' => '10']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2)): ?>
<?php $attributes = $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2; ?>
<?php unset($__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2)): ?>
<?php $component = $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2; ?>
<?php unset($__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2); ?>
<?php endif; ?>

                <div class="hidden text-left lg:block">

                    <h3 class="font-semibold text-slate-800">

                        <?php echo e($displayName); ?>


                    </h3>

                    <p class="text-sm text-slate-500">

                        <?php echo e($user->role?->name); ?>


                    </p>

                </div>

                <i
                    data-lucide="chevron-down"
                    class="hidden h-4 w-4 text-slate-500 lg:block">
                </i>

            </button>

            
            <div

                x-show="open"

                @click.outside="open=false"

                x-transition

                class="absolute right-0 mt-3 w-72 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl"

                style="display:none;">

                
                <div class="border-b border-slate-100 p-5">

                    <div class="flex items-center gap-3">

                        <?php if (isset($component)) { $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.avatar','data' => ['employee' => $user->employee,'size' => '10']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['employee' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->employee),'size' => '10']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2)): ?>
<?php $attributes = $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2; ?>
<?php unset($__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2)): ?>
<?php $component = $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2; ?>
<?php unset($__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2); ?>
<?php endif; ?>

                        <div>

                            <h3 class="font-semibold text-slate-800">

                                <?php echo e($displayName); ?>


                            </h3>

                            <p class="text-sm text-slate-500">

                                <?php echo e($displayEmail); ?>


                            </p>

                            <span
                                class="mt-2 inline-block rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">

                                <?php echo e($user->role?->name); ?>


                            </span>

                        </div>

                    </div>

                </div>

                
                <div class="py-2">

                    <a href="#" class="flex items-center gap-3 px-5 py-3 transition hover:bg-slate-50">

                        <i
                            data-lucide="user"
                            class="h-5 w-5">
                        </i>

                        My Profile

                    </a>

                    <a href="#" class="flex items-center gap-3 px-5 py-3 transition hover:bg-slate-50">

                        <i
                            data-lucide="settings"
                            class="h-5 w-5">
                        </i>

                        Account Settings

                    </a>

                </div>

                
                <div class="border-t border-slate-100">

                    <form
                        method="POST"
                        action="<?php echo e(route('logout')); ?>">

                        <?php echo csrf_field(); ?>

                        <button
                            type="submit"
                            class="flex w-full items-center gap-3 px-5 py-4 text-left text-red-600 transition hover:bg-red-50">

                            <i
                                data-lucide="log-out"
                                class="h-5 w-5">
                            </i>

                            Logout

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</header><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/partials/navbar.blade.php ENDPATH**/ ?>