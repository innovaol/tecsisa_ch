<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ Auth::user()->theme ?? 'dark' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#FFD100">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="TECSISA CH">
        <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

        <link rel="manifest" href="/manifest.json">

        <title>{{ $company_name ?? config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
                            // Map generic theme names
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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Flatpickr for forced dd/mm/yyyy visually -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
        
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
                --theme-grid: rgba(255, 255, 255, 0.05);
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
                --theme-grid: rgba(0, 0, 0, 0.05);
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
                --theme-grid: rgba(255, 255, 255, 0.05);
                --theme-scrollbar-bg: #0A0F1C;
            }

            /* Global Styles using variables */
            body {
                background-color: var(--theme-bg);
                color: var(--theme-text);
                transition: background-color 0.3s ease, color 0.3s ease;
            }
            .text-theme { color: var(--theme-text); }
            .bg-theme { background-color: var(--theme-bg); }
            .bg-theme-card { background-color: var(--theme-card); }
            .border-theme { border-color: var(--theme-border); }
            .bg-theme-header { background-color: var(--theme-header); }
            .text-theme-muted { color: var(--theme-text-muted); }

            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: var(--theme-scrollbar-bg); }
            ::-webkit-scrollbar-thumb { background: #FFD100; border-radius: 4px; }
            
            [x-cloak] { display: none !important; }

            /* Safe Area & Mobile Stability Utilities */
            @supports (padding: env(safe-area-inset-top)) {
                .pt-safe { padding-top: env(safe-area-inset-top); }
                .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
                .pb-safe-bottom { padding-bottom: env(safe-area-inset-bottom); }
            }
            
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            
            html, body {
                max-width: 100%;
                overflow-x: hidden;
            }
            
            @media (max-width: 768px) {
                body {
                    /* Removed fixed positioning to allow natural scroll while preventing horizontal shift */
                    width: 100%;
                    overscroll-behavior-y: auto;
                }
            }
        </style>
    </head>
    <body x-data="{ 
            theme: '{{ Auth::user()->theme ?? 'dark' }}',
            toggleTheme() {
                this.theme = this.theme === 'dark' ? 'light' : 'dark';
                // Update HTML class for Tailwind
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
        class="font-sans antialiased overflow-x-hidden min-h-screen flex flex-col">
        
        <!-- Background Glowing Orbs -->
        <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-tecsisa-yellow/5 blur-[120px] pointer-events-none z-0" :class="theme === 'light' ? 'opacity-20' : ''"></div>
        <div class="fixed bottom-[-10%] right-[-10%] w-[30%] h-[30%] rounded-full bg-blue-500/5 blur-[120px] pointer-events-none z-0" :class="theme === 'light' ? 'opacity-20' : ''"></div>

        <div class="flex-1 flex flex-col relative z-20 overflow-hidden h-full">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-theme-header backdrop-blur-md border-b border-theme transition-colors duration-300">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1 w-full relative md:overflow-visible overflow-y-auto no-scrollbar sm:pb-0 pb-32 pb-safe transition-all mt-0">
                {{ $slot }}
                <!-- Spacer for mobile stability -->
                <div class="h-20 md:hidden"></div>
            </main>
        </div>

        @stack('scripts')
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/service-worker.js')
                        .then(reg => console.log('Service Worker registered', reg))
                        .catch(err => console.log('Service Worker registration failed', err));
                });
            }
        </script>
    </body>
</html>
