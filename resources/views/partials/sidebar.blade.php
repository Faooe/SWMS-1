@php

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

@endphp

{{-- Overlay --}}
<div

    id="sidebar-overlay"

    class="fixed inset-0 z-40 hidden bg-black/40 backdrop-blur-sm lg:hidden">

</div>

{{-- Sidebar --}}
<aside
    id="sidebar"
    class="fixed left-0 top-0 flex h-screen flex-col bg-white">

    {{-- ========================================= --}}
    {{-- Header --}}
    {{-- ========================================= --}}

    <div
        class="border-b border-slate-200">

        <div

            class="sidebar-brand flex h-20 items-center justify-between px-5">

            {{-- Brand --}}
            <div
                class="flex items-center gap-3 overflow-hidden">

                {{-- Logo --}}
                <div

                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-md shadow-blue-200">

                    <i
                        data-lucide="building-2"
                        class="h-6 w-6">
                    </i>

                </div>

                {{-- Text --}}
                <div
                    class="sidebar-text overflow-hidden">

                    <h2

                        class="truncate text-xl font-extrabold tracking-tight text-slate-800">

                        SWMS

                    </h2>

                    <p

                        class="sidebar-description truncate text-xs text-slate-500">

                        {{ $user->isPlatformAdmin()

                            ? 'Platform Console'

                            : 'Smart Workforce Management'

                        }}

                    </p>

                </div>

            </div>

            {{-- Collapse Button --}}
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
    {{-- ========================================================= --}}
    {{-- Navigation --}}
    {{-- ========================================================= --}}

    <div
        id="sidebar-scroll"
        class="flex-1 overflow-y-auto py-5">

        @foreach($menus as $group)

            <div
                class="mb-7">

                {{-- Group Title --}}
                <div
                    class="sidebar-group mb-3 px-5">

                    <span
                        class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">

                        {{ $group['title'] }}

                    </span>

                </div>

                {{-- Menu --}}
                <div
                    class="space-y-1 px-3">

                    @foreach($group['items'] as $menu)

                        @php

                            $active =
                                $menu['route'] !== '#'
                                && request()->routeIs($menu['route']);

                        @endphp

                        <x-ui.sidebar-item

                            :href="$menu['route'] === '#'
                                ? '#'
                                : route($menu['route'])"

                            :icon="$menu['icon']"

                            :title="$menu['name']"

                            :active="$active"

                        />

                    @endforeach

                </div>

            </div>

        @endforeach

    </div>
{{-- ========================================================= --}}
{{-- Footer --}}
{{-- ========================================================= --}}

<div
    class="border-t border-slate-200 bg-white p-4">

    <div

        class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3 transition hover:bg-slate-100">

        {{-- Avatar --}}
        <div
            class="shrink-0">

            <x-ui.avatar
                :employee="$user->employee"
                size="10"
            />

        </div>

        {{-- User --}}
        <div
            class="sidebar-user min-w-0 flex-1">

            <h4
                class="truncate text-sm font-semibold text-slate-800">

                {{ $displayName }}

            </h4>

            <p
                class="truncate text-xs text-slate-500">

                {{ $user->role->name }}

            </p>

        </div>

    </div>

</div>

</aside>