<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>

        @yield('title', 'SWMS')

    </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Font --}}
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Leaflet --}}
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    {{-- Leaflet Draw --}}
    <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css">

    {{-- Vite --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    {{-- Livewire --}}
    @livewireStyles

</head>

<body
    class="bg-slate-100 font-[Inter] antialiased"
    data-flash-success="{{ session('success') ? 'true' : 'false' }}">

    <div
        class="layout">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Main --}}
        <div
            id="page-wrapper"
            class="page-wrapper">

            {{-- Navbar --}}
            @include('partials.navbar')

            {{-- Content --}}
            <main
                class="page-content">

                @yield('content')

            </main>

        </div>

    </div>

    {{-- Overlay --}}
    <div
        id="sidebar-overlay"
        class="sidebar-overlay hidden">
    </div>

    {{-- Lottie loading / complete overlay --}}
    @include('partials.loading-overlay')

    {{-- Subscription Badge (Company Admin only) --}}
    @auth
        @if(auth()->user()->isCompanyAdmin())
            @include('components.subscription-badge')
        @endif
    @endauth

    {{-- Leaflet --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

    @stack('scripts')

    {{-- Livewire --}}
    @livewireScripts

</body>

</html>