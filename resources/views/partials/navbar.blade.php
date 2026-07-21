@php

$user = auth()->user();

$displayName = $user->employee?->full_name
    ?? $user->username
    ?? 'Platform Administrator';

$displayEmail = $user->email
    ?? '-';

@endphp

<header
    class="sticky top-0 z-30 flex h-20 items-center justify-between gap-3 border-b border-slate-200 bg-white px-4 lg:px-8">

    {{-- LEFT --}}
    <div class="flex min-w-0 flex-1 items-center gap-3 lg:gap-5">

        {{-- Mobile Toggle --}}
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

                @hasSection('page-title')

                    /

                    @yield('page-title')

                @endif

            </p>

            <h1 class="truncate text-xl font-bold tracking-tight text-slate-800 lg:text-2xl">

                @yield('page-title','Dashboard')

            </h1>

        </div>

    </div>

    {{-- RIGHT --}}
    <div class="flex shrink-0 items-center gap-2 lg:gap-3">

        {{-- Search --}}
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

        {{-- Notification --}}
        <div
            x-data="notificationDropdown({
                indexUrl: '{{ route('notifications.index') }}',
                unreadCountUrl: '{{ route('notifications.unread-count') }}',
                readUrl: '{{ route('notifications.read', ':id') }}',
                readAllUrl: '{{ route('notifications.read-all') }}',
            })"
            class="relative shrink-0">

            <button
                @click="toggle()"
                class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white transition hover:bg-slate-100">

                <span
                    x-show="unreadCount > 0"
                    class="absolute right-2 top-2 flex h-4 min-w-[16px] items-center justify-center rounded-full border-2 border-white bg-red-500 px-1 text-[10px] font-bold text-white"
                    style="display:none;">
                    <span x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                </span>

                <i
                    data-lucide="bell"
                    class="h-5 w-5">
                </i>

            </button>

            {{-- Dropdown --}}
            <div
                x-show="open"
                @click.outside="open = false"
                x-transition
                class="absolute right-0 mt-3 w-96 max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl"
                style="display:none;">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">

                    <h3 class="font-semibold text-slate-800">Notifikasi</h3>

                    <button
                        @click="markAllAsRead()"
                        x-show="unreadCount > 0"
                        class="text-xs font-medium text-blue-600 hover:underline"
                        style="display:none;">
                        Tandai semua dibaca
                    </button>

                </div>

                {{-- List --}}
                    <div class="max-h-[180px] overflow-y-auto">

                    <template x-if="loading">
                        <div class="p-6 text-center text-sm text-slate-400">Memuat...</div>
                    </template>

                    <template x-if="!loading && items.length === 0">
                        <div class="p-6 text-center text-sm text-slate-400">Belum ada notifikasi.</div>
                    </template>

                    <template x-for="item in items" :key="item.id">
                        <button
                            @click="openItem(item)"
                            class="flex w-full items-start gap-3 border-b border-slate-50 px-5 py-4 text-left transition hover:bg-slate-50"
                            :class="!item.is_read ? 'bg-blue-50/60' : ''">

                            <span
                                class="mt-1.5 h-2 w-2 shrink-0 rounded-full"
                                :class="!item.is_read ? 'bg-blue-500' : 'bg-transparent'">
                            </span>

                            <div class="min-w-0 flex-1">

                                <p class="truncate text-sm font-semibold text-slate-800" x-text="item.title"></p>

                                <p class="mt-0.5 line-clamp-2 text-xs text-slate-500" x-text="item.message"></p>

                                <p class="mt-1 text-[11px] text-slate-400" x-text="timeAgo(item.created_at)"></p>

                            </div>

                        </button>
                    </template>

                </div>

            </div>

        </div>

        {{-- Settings --}}
        <button
            class="hidden h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white transition hover:bg-slate-100 sm:flex">

            <i
                data-lucide="settings-2"
                class="h-5 w-5">
            </i>

        </button>

        {{-- USER MENU --}}
        <div
            x-data="{ open:false }"
            class="relative shrink-0">

            <button

                @click="open=!open"

                class="flex items-center gap-2 rounded-2xl px-1 py-1 transition hover:bg-slate-100 lg:gap-3 lg:px-2 lg:py-2">

                <x-ui.avatar
                    :employee="$user->employee"
                    size="10"
                />

                <div class="hidden text-left lg:block">

                    <h3 class="font-semibold text-slate-800">

                        {{ $displayName }}

                    </h3>

                    <p class="text-sm text-slate-500">

                        {{ $user->role?->name }}

                        @if(!$user->isPlatformAdmin() && $user->company)
                            &middot; {{ $user->company->name }}
                        @endif

                    </p>

                </div>

                <i
                    data-lucide="chevron-down"
                    class="hidden h-4 w-4 text-slate-500 lg:block">
                </i>

            </button>

            {{-- Dropdown --}}
            <div

                x-show="open"

                @click.outside="open=false"

                x-transition

                class="absolute right-0 mt-3 w-72 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl"

                style="display:none;">

                {{-- Header --}}
                <div class="border-b border-slate-100 p-5">

                    <div class="flex items-center gap-3">

                        <x-ui.avatar
                            :employee="$user->employee"
                            size="10"
                        />

                        <div>

                            <h3 class="font-semibold text-slate-800">

                                {{ $displayName }}

                            </h3>

                            <p class="text-sm text-slate-500">

                                {{ $displayEmail }}

                            </p>

                            @if(!$user->isPlatformAdmin() && $user->company)
                                <p class="mt-1 flex items-center gap-1.5 text-sm font-medium text-slate-600">
                                    <i data-lucide="building-2" class="h-3.5 w-3.5"></i>
                                    {{ $user->company->name }}
                                </p>
                            @endif

                            <span
                                class="mt-2 inline-block rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">

                                {{ $user->role?->name }}

                            </span>

                        </div>

                    </div>

                </div>

                {{-- Menu --}}
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

                {{-- Logout --}}
                <div class="border-t border-slate-100">

                    <form
                        method="POST"
                        action="{{ route('logout') }}">

                        @csrf

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

</header>