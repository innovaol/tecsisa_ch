<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <!-- Scripts (Using CDN for local development without Node.js) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            tecsisa: {
                                yellow: '#FFD100',
                                dark: '#0A0F1C',     // Even darker than standard slate
                                card: 'rgba(17, 24, 39, 0.7)',  // Glassmorphism card
                            }
                        },
                        fontFamily: {
                            sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #0A0F1C; }
            ::-webkit-scrollbar-thumb { background: #FFD100; border-radius: 4px; }
            
            /* Background Pattern */
            .bg-grid-pattern {
                background-image: 
                    linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
                background-size: 40px 40px;
            }
        </style>
    </head>
    <body class="font-sans text-gray-300 antialiased bg-tecsisa-dark bg-grid-pattern relative min-h-screen flex items-center justify-center overflow-hidden">
        
        <!-- Background Glowing Orbs -->
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-tecsisa-yellow/10 blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[30%] h-[30%] rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>

        <div class="w-full sm:max-w-md px-8 py-10 bg-tecsisa-card backdrop-blur-xl border border-white/10 shadow-2xl relative z-10 sm:rounded-2xl">
            <div class="flex justify-center mb-8">
                <div class="font-bold text-4xl tracking-widest flex items-center gap-2">
                    <span class="text-white">TEC</span><span class="text-tecsisa-yellow drop-shadow-[0_0_8px_rgba(255,209,0,0.5)]">SISA</span>
                </div>
            </div>

            {{ $slot }}
        </div>
    </body>
</html>
