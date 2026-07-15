<?php

use Illuminate\Support\Facades\Auth;

/** @var \App\Models\User $user */

$user = Auth::user();

/*
|--------------------------------------------------------------------------
| PLATFORM ADMIN MENU
|--------------------------------------------------------------------------
*/

$platformMenus = [

    [

        'title' => 'PLATFORM',

        'items' => [

            [

                'name' => 'Dashboard',

                'icon' => 'layout-dashboard',

                'route' => 'platform.dashboard',

            ],

            [

                'name' => 'Companies',

                'icon' => 'building-2',

                'route' => 'platform.companies.index',

            ],

            [

                'name' => 'Premium',

                'icon' => 'gem',

                'route' => 'platform.premium.index',

            ],

        ],

    ],

    [

        'title' => 'ACCOUNT',

        'items' => [

            [

                'name' => 'Profile',

                'icon' => 'user-circle',

                'route' => 'platform.profile.edit',

            ],

        ],

        [

    'title'=>'ACCOUNT',

    'items'=>[

        [

            'name'=>'Profile',

            'icon'=>'user-circle',

            'route'=>'profile.edit',

        ],

    ],

],

    ],

];



/*
|--------------------------------------------------------------------------
| SUPER ADMIN MENU
|--------------------------------------------------------------------------
*/

$superAdminMenus = [

    [

        'title' => 'MAIN MENU',

        'items' => [

            [

                'name'=>'Dashboard',

                'icon'=>'layout-dashboard',

                'route'=>'dashboard',

            ],

            [

                'name'=>'Employee',

                'icon'=>'users',

                'route'=>'employees.index',

            ],

            [

                'name'=>'Attendance',

                'icon'=>'calendar-check',

                'route'=>'attendance.index',

            ],

            [

                'name'=>'Assignment',

                'icon'=>'clipboard-list',

                'route'=>'assignments.index',

            ],

            [

                'name'=>'Leave / Permission',

                'icon'=>'file-text',

                'route'=>'leaves.index',

            ],

        ],

    ],

    [

        'title'=>'MASTER DATA',

        'items'=>[

            [

                'name'=>'Office',

                'icon'=>'building-2',

                'route'=>'offices.index',

            ],

            [

                'name'=>'Department',

                'icon'=>'briefcase-business',

                'route'=>'departments.index',

            ],

        ],

    ],

];

/*
|--------------------------------------------------------------------------
| EMPLOYEE MENU
|--------------------------------------------------------------------------
*/

$employeeMenus=[

    [

        'title'=>'MAIN MENU',

        'items'=>[

            [

                'name'=>'Dashboard',

                'icon'=>'layout-dashboard',

                'route'=>'employee.dashboard',

            ],

            [

                'name'=>'Attendance',

                'icon'=>'calendar-check',

                'route'=>'employee.attendance.index',

            ],

            [

                'name'=>'My Assignment',

                'icon'=>'clipboard-list',

                'route'=>'employee.assignments.index',

            ],

            [

                'name'=>'Attendance History',

                'icon'=>'history',

                'route'=>'employee.attendance.history',

            ],

            [

                'name'=>'Leave / Permission',

                'icon'=>'file-text',

                'route'=>'employee.leaves.index',

            ],

        ],

    ],

    [

        'title'=>'PROFILE',

        'items'=>[

            [

                'name'=>'My Profile',

                'icon'=>'user-circle',

                'route'=>'employee.profile',

            ],

        ],

    ],

];

$menus =

    $user->isPlatformAdmin()

        ? $platformMenus

        : (

            $user->isSuperAdmin()

                ? $superAdminMenus

                : $employeeMenus

        );

$displayName =

    $user->employee?->full_name

    ?? 'Platform Administrator';

?>


<div

    id="sidebar-overlay"

    class="fixed inset-0 z-40 hidden bg-black/40 backdrop-blur-sm lg:hidden">

</div>


<aside
    id="sidebar"
    class="fixed left-0 top-0 flex h-screen flex-col bg-white">

    
    
    

    <div
        class="border-b border-slate-200">

        <div

            class="sidebar-brand flex h-20 items-center justify-between px-5">

            
            <div
                class="flex items-center gap-3 overflow-hidden">

                
                <div

                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-md shadow-blue-200">

                    <i
                        data-lucide="building-2"
                        class="h-6 w-6">
                    </i>

                </div>

                
                <div
                    class="sidebar-text overflow-hidden">

                    <h2

                        class="truncate text-xl font-extrabold tracking-tight text-slate-800">

                        SWMS

                    </h2>

                    <p

                        class="sidebar-description truncate text-xs text-slate-500">

                        <?php echo e($user->isPlatformAdmin()

                            ? 'Platform Console'

                            : 'Smart Workforce Management'); ?>


                    </p>

                </div>

            </div>

            
            <button

                id="sidebar-collapse"

                class="hidden h-10 w-10 items-center justify-center rounded-xl border border-slate-200 transition hover:bg-slate-100 lg:flex">

                <i

                    data-lucide="panel-left-close"

                    class="h-5 w-5">

                </i>

            </button>

        </div>

    </div>
    
    
    

    <div
        id="sidebar-scroll"
        class="flex-1 overflow-y-auto py-5">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div
                class="mb-7">

                
                <div
                    class="sidebar-group mb-3 px-5">

                    <span
                        class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">

                        <?php echo e($group['title']); ?>


                    </span>

                </div>

                
                <div
                    class="space-y-1 px-3">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php

                            $active =
                                $menu['route'] !== '#'
                                && request()->routeIs($menu['route']);

                        ?>

                        <?php if (isset($component)) { $__componentOriginalb11b72e4b31cef32aadf7ea0b2ef46b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb11b72e4b31cef32aadf7ea0b2ef46b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.sidebar-item','data' => ['href' => $menu['route'] === '#'
                                ? '#'
                                : route($menu['route']),'icon' => $menu['icon'],'title' => $menu['name'],'active' => $active]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu['route'] === '#'
                                ? '#'
                                : route($menu['route'])),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu['icon']),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu['name']),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($active)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb11b72e4b31cef32aadf7ea0b2ef46b3)): ?>
<?php $attributes = $__attributesOriginalb11b72e4b31cef32aadf7ea0b2ef46b3; ?>
<?php unset($__attributesOriginalb11b72e4b31cef32aadf7ea0b2ef46b3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb11b72e4b31cef32aadf7ea0b2ef46b3)): ?>
<?php $component = $__componentOriginalb11b72e4b31cef32aadf7ea0b2ef46b3; ?>
<?php unset($__componentOriginalb11b72e4b31cef32aadf7ea0b2ef46b3); ?>
<?php endif; ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </div>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>




<div
    class="border-t border-slate-200 bg-white p-4">

    <div

        class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3 transition hover:bg-slate-100">

        
        <div
            class="shrink-0">

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

        </div>

        
        <div
            class="sidebar-user min-w-0 flex-1">

            <h4
                class="truncate text-sm font-semibold text-slate-800">

                <?php echo e($displayName); ?>


            </h4>

            <p
                class="truncate text-xs text-slate-500">

                <?php echo e($user->role->name); ?>


            </p>

        </div>

    </div>

</div>

</aside><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>