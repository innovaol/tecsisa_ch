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

    <!-- Scripts (Using CDN for local development without Node.js) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        tecsisa: {
                            yellow: '#FFD100',
                            dark: '#0A0F1C',
                        },
                        'theme-bg': 'var(--theme-bg)',
                        'theme-text': 'var(--theme-text)',
                        'theme-muted': 'var(--theme-text-muted)',
                        'theme-card': 'var(--theme-card)',
                        'theme-border': 'var(--theme-border)',
                        'theme-header': 'var(--theme-header)',
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Variables System */
        :root {
            --theme-bg: #0A0F1C;
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
            overscroll-behavior-y: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        @media (max-width: 768px) {
            body {
                position: fixed;
                width: 100%;
                height: 100dvh;
                overflow: hidden;
                overscroll-behavior-y: none;
            }
        }

        .text-theme { color: var(--theme-text); }
        .bg-theme { background-color: var(--theme-bg); }
        .bg-theme-card { background-color: var(--theme-card); }
        .border-theme { border-color: var(--theme-border); }
        .bg-theme-header { background-color: var(--theme-header); }
        .text-theme-muted { color: var(--theme-text-muted); }

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
    class="font-sans antialiased md:overflow-auto md:h-auto overflow-hidden h-[100dvh]" style="display: flex; flex-direction: column;">
    
    <!-- Background Glowing Orbs -->
    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-tecsisa-yellow/5 blur-[120px] pointer-events-none z-0" :class="theme === 'light' ? 'opacity-20' : ''"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[30%] h-[30%] rounded-full bg-blue-500/5 blur-[120px] pointer-events-none z-0" :class="theme === 'light' ? 'opacity-20' : ''"></div>

    <!-- Unified Master Navigation -->
    @include('layouts.navigation', ['hideHeader' => $hideHeader ?? false])

    <!-- Contenido Principal -->
    <main class="flex-1 md:overflow-visible overflow-y-auto no-scrollbar relative z-10 {{ ($hideNav ?? false) ? '' : 'pb-32 pb-safe md:pb-0' }}">
        <div class="md:max-w-7xl md:mx-auto md:w-full md:px-6 lg:px-8">
            {{ $slot }}
            <!-- Spacer for mobile stability -->
            <div class="h-24 md:hidden"></div>
        </div>
    </main>

    @stack('scripts')
</body>
</html>
