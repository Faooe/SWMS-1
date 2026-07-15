@extends('layouts.app')

@section('title', 'Company Detail')

@section('content')
<x-platform.company-created-modal />

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <x-ui.page-header

        title="Company Detail"

        description="Informasi lengkap tenant perusahaan."

    >

        <div class="flex gap-3">

            <a
                href="{{ route('platform.companies.edit',$company) }}">

                <x-ui.button>

                    <i
                        data-lucide="square-pen"
                        class="h-5 w-5">
                    </i>

                    Edit

                </x-ui.button>

            </a>

            <a
                href="{{ route('platform.companies.index') }}">

                <x-ui.button
                    variant="secondary">

                    Back

                </x-ui.button>

            </a>

        </div>

    </x-ui.page-header>

    {{-- ========================================================= --}}
    {{-- Password Alert --}}
    {{-- ========================================================= --}}

    @if(session('generated_password'))

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

                        {{ session('generated_password') }}

                    </div>

                </div>

            </div>

        </div>

    @endif

    {{-- ========================================================= --}}
    {{-- Hero --}}
    {{-- ========================================================= --}}

    <x-ui.card>

        <div class="flex flex-col gap-6 md:flex-row md:items-center">

            @if($company->logo)

                <img

                    src="{{ asset('storage/'.$company->logo) }}"

                    class="h-28 w-28 rounded-3xl object-cover">

            @else

                <div

                    class="flex h-28 w-28 items-center justify-center rounded-3xl bg-blue-100 text-4xl font-bold text-blue-600">

                    {{ strtoupper(substr($company->name,0,1)) }}

                </div>

            @endif

            <div class="flex-1">

                <h2

                    class="text-3xl font-bold">

                    {{ $company->name }}

                </h2>

                <p

                    class="mt-2 text-slate-500">

                    {{ $company->code }}

                </p>

                <div

                    class="mt-5 flex flex-wrap gap-3">

                    @if($company->is_active)

                        <x-ui.badge color="green">

                            Active

                        </x-ui.badge>

                    @else

                        <x-ui.badge color="red">

                            Inactive

                        </x-ui.badge>

                    @endif

                    <x-ui.badge color="blue">

                        {{ $company->subscription_plan }}

                    </x-ui.badge>

                </div>

            </div>

        </div>

    </x-ui.card>

    {{-- ========================================================= --}}
    {{-- Information --}}
    {{-- ========================================================= --}}

    <div class="grid gap-6 lg:grid-cols-2">

        <x-ui.card>

            <h3 class="mb-6 text-lg font-bold">

                Company Information

            </h3>

            <div class="space-y-4">

                <x-ui.detail-item

                    label="Company Code"

                    :value="$company->code"

                />

                <x-ui.detail-item

                    label="Email"

                    :value="$company->email"

                />

                <x-ui.detail-item

                    label="Phone"

                    :value="$company->phone"

                />

                <x-ui.detail-item

                    label="Website"

                    :value="$company->website"

                />

            </div>

        </x-ui.card>

        <x-ui.card>

            <h3 class="mb-6 text-lg font-bold">

                Address

            </h3>

            <div class="space-y-4">

                <x-ui.detail-item

                    label="Address"

                    :value="$company->address"

                />

                <x-ui.detail-item

                    label="City"

                    :value="$company->city"

                />

                <x-ui.detail-item

                    label="Province"

                    :value="$company->province"

                />

                <x-ui.detail-item

                    label="Postal Code"

                    :value="$company->postal_code"

                />

            </div>

        </x-ui.card>

    </div>

    {{-- ========================================================= --}}
    {{-- Statistics --}}
    {{-- ========================================================= --}}

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">

        <x-ui.stat-card

            title="Employees"

            :value="$company->employees_count"

        />

        <x-ui.stat-card

            title="Users"

            :value="$company->users_count"

        />

        <x-ui.stat-card

            title="Offices"

            :value="$company->offices_count"

        />

        <x-ui.stat-card

            title="Assignments"

            :value="$company->assignments_count"

        />

    </div>
        <x-ui.card>

        <h3 class="mb-6 text-lg font-bold">

            Super Administrator

        </h3>

        @php

            $admin = $company->users
                ->firstWhere('role.code','SUPER_ADMIN');

        @endphp

        @if($admin)

            <div class="space-y-4">

                <x-ui.detail-item

                    label="Full Name"

                    :value="$admin->employee?->full_name"

                />

                <x-ui.detail-item

                    label="Username"

                    :value="$admin->username"

                />

                <x-ui.detail-item

                    label="Email"

                    :value="$admin->email"

                />

                <x-ui.detail-item

                    label="Phone"

                    :value="$admin->employee?->phone"

                />

                <x-ui.detail-item

                    label="Last Login"

                    :value="$admin->last_login_at"

                />

            </div>

        @else

            <p class="text-slate-500">

                Super Administrator belum tersedia.

            </p>

        @endif

    </x-ui.card>
        <x-ui.card>

        <h3 class="mb-6 text-lg font-bold">

            Head Office

        </h3>

        @php

            $office = $company->offices
                ->firstWhere('is_head_office',true);

        @endphp

        @if($office)

            <div class="space-y-4">

                <x-ui.detail-item

                    label="Office Name"

                    :value="$office->name"

                />

                <x-ui.detail-item

                    label="Radius"

                    :value="$office->radius.' Meter'"

                />

                <x-ui.detail-item

                    label="Timezone"

                    :value="$office->timezone"

                />

                <x-ui.detail-item

                    label="Latitude"

                    :value="$office->latitude"

                />

                <x-ui.detail-item

                    label="Longitude"

                    :value="$office->longitude"

                />

            </div>

        @endif

    </x-ui.card>
    <x-ui.card>

    <h3 class="mb-6 text-lg font-bold">

        Subscription

    </h3>

    <div class="space-y-4">

        <x-ui.detail-item

            label="Plan"

            :value="$company->subscription_plan"

        />

        <x-ui.detail-item

            label="Start"

            :value="$company->subscription_start"

        />

        <x-ui.detail-item

            label="Expired"

            :value="$company->subscription_end"

        />

        <x-ui.detail-item

            label="Employee Limit"

            :value="$company->max_employee"

        />

    </div>

</x-ui.card>
<x-ui.card>

    <h3 class="mb-6 text-lg font-bold">

        Activity

    </h3>

    <div class="space-y-4">

        <x-ui.detail-item

            label="Created At"

            :value="$company->created_at"

        />

        <x-ui.detail-item

            label="Updated At"

            :value="$company->updated_at"

        />

        <x-ui.detail-item

            label="Status"

            :value="$company->is_active ? 'Active' : 'Inactive'"

        />

    </div>

</x-ui.card>
<x-ui.card>

<div class="flex flex-wrap justify-end gap-3">

    <a

        href="{{ route('platform.companies.edit',$company) }}">

        <x-ui.button>

            Edit Company

        </x-ui.button>

    </a>

    <form

        action="{{ route('platform.companies.destroy',$company) }}"

        method="POST"

        onsubmit="return confirm('Delete this company?')">

        @csrf

        @method('DELETE')

        <x-ui.button

            variant="danger">

            Delete

        </x-ui.button>

    </form>

    <a

        href="{{ route('platform.companies.index') }}">

        <x-ui.button

            variant="secondary">

            Back

        </x-ui.button>

    </a>

</div>

</x-ui.card>

</div>

@endsection