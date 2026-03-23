<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ Auth::user()->theme ?? 'dark' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $company_name ?? config('app.name', 'Tecsisa App') }} - Técnico</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts (Using Vite for precompiled optimized CSS/JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Variables System */
        :root {
            --theme-bg: #0A0F1C;
            --theme-bg-rgb: 10, 15, 28;
            --theme-text: #d1d5db;
            --theme-text-muted: #9ca3af;
            --theme-card: rgba(17, 24, 39, 0.82);
            --theme-border: rgba(255, 255, 255, 0.1);
            --theme-header: rgba(10, 15, 28, 0.85);
            --theme-nav-active: #ffffff;
            --theme-nav-inactive: #6b7280;
            --theme-table-row-hover: rgba(255, 255, 255, 0.02);
            --theme-grid: rgba(255, 255, 255, 0.02);
            --theme-scrollbar-bg: #0A0F1C;
        }

        .light {
            --theme-bg: #f3f4f6;
            --theme-bg-rgb: 243, 244, 246;
            --theme-text: #1a1a1a;
            --theme-text-muted: #64748b;
            --theme-card: #ffffff;
            --theme-border: #e2e8f0;
            --theme-header: rgba(243, 244, 246, 0.9);
            --theme-nav-active: #000000;
            --theme-nav-inactive: #64748b;
            --theme-table-row-hover: rgba(0, 0, 0, 0.02);
            --theme-grid: rgba(0, 0, 0, 0.02);
            --theme-scrollbar-bg: #f3f4f6;
        }

        .dark {
            --theme-bg: #0A0F1C;
            --theme-bg-rgb: 10, 15, 28;
            --theme-text: #d1d5db;
            --theme-text-muted: #9ca3af;
            --theme-card: rgba(17, 24, 39, 0.82);
            --theme-border: rgba(255, 255, 255, 0.1);
            --theme-header: rgba(10, 15, 28, 0.85);
            --theme-nav-active: #ffffff;
            --theme-nav-inactive: #6b7280;
            --theme-table-row-hover: rgba(255, 255, 255, 0.02);
            --theme-grid: rgba(255, 255, 255, 0.02);
            --theme-scrollbar-bg: #0A0F1C;
        }

        * { -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        body {
            background-color: var(--theme-bg);
            color: var(--theme-text);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        html, body {
            max-width: 100%;
            overflow-x: hidden;
            scrollbar-gutter: stable;
        }

        @media (max-width: 768px) {
            body {
                width: 100%;
                overscroll-behavior-y: auto;
            }
        }

        .text-theme { color: var(--theme-text); }
        .bg-theme { background-color: var(--theme-bg); }
        .bg-theme-card { background-color: var(--theme-card); }
        .border-theme { border-color: var(--theme-border); }
        .bg-theme-header { background-color: var(--theme-header); }
        .text-theme-muted { color: var(--theme-text-muted); }

        /* Force Transparent Inputs */
        [type='text'], [type='email'], [type='number'], [type='password'], textarea, select {
            background-color: transparent !important;
            color: var(--theme-text) !important;
        }

        /* Autocomplete Chrome Autofill Override */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        textarea:-webkit-autofill,
        textarea:-webkit-autofill:hover,
        textarea:-webkit-autofill:focus,
        select:-webkit-autofill,
        select:-webkit-autofill:hover,
        select:-webkit-autofill:focus {
            -webkit-text-fill-color: var(--theme-text) !important;
            -webkit-box-shadow: 0 0 0px 1000px var(--theme-bg) inset !important;
            transition: background-color 5000s ease-in-out 0s !important;
            background-color: transparent !important;
        }

        /* Safe Area Utilities */
        @supports (padding: env(safe-area-inset-top)) {
            .pt-safe { padding-top: env(safe-area-inset-top); }
            .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
            .pb-safe-bottom { padding-bottom: env(safe-area-inset-bottom); }
        }
    </style>
</head>
<body x-data="{ 
        theme: '{{ Auth::user()->theme ?? 'dark' }}',
        toggleTheme() {
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            document.documentElement.className = this.theme;
            
            fetch('{{ route('profile.theme.update') }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ theme: this.theme })
            });
        }
    }" 
    :class="theme"
    class="font-sans antialiased flex flex-col min-h-screen">
    
    <!-- Background Glowing Orbs -->
    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-tecsisa-yellow/5 blur-[120px] pointer-events-none z-0" :class="theme === 'light' ? 'opacity-20' : ''"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[30%] h-[30%] rounded-full bg-blue-500/5 blur-[120px] pointer-events-none z-0" :class="theme === 'light' ? 'opacity-20' : ''"></div>

    <!-- Unified Master Navigation -->
    @include('layouts.navigation', ['hideHeader' => $hideHeader ?? false])

    <!-- Contenido Principal -->
    <main class="flex-1 relative z-10 {{ ($hideNav ?? false) ? '' : 'pb-32 pb-safe md:pb-0' }}">
        <div class="md:max-w-7xl md:mx-auto md:w-full md:px-6 lg:px-8">
            {{ $slot }}
            <!-- Spacer for mobile stability -->
            <div class="h-24 md:hidden"></div>
        </div>
    </main>

    @stack('scripts')
    
    <!-- PWA Service Worker Killer - Force clear broken SWs -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                for(let registration of registrations) {
                    registration.unregister().then(function(boolean) {
                        console.log('- Broken Service worker unregistered:', boolean);
                    });
                }
            });
        }
    </script>
</body>
</html>
