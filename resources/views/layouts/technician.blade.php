<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tecsisa App') }} - Técnico</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts (Using CDN for local development without Node.js) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        tecsisa: {
                            yellow: '#FFD100',
                            dark: '#0A0F1C',
                            card: 'rgba(17, 24, 39, 0.7)',
                        }
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
        /* Desactiva el outline de selección en móviles para un feel más nativo */
        * {
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Oculta scrollbars nativas pero permite scroll */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        body {
            background-color: #05080f;
            color: #ffffff;
            /* Previene rebote de scroll (overscroll) en iOS/Android */
            overscroll-behavior-y: none;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#05080f] text-white overflow-hidden" style="display: flex; flex-direction: column; height: 100dvh;">

    <!-- Top Header Nativo -->
    <header class="bg-[#0f1217]/90 backdrop-blur-xl border-b border-white/5 pt-safe shrink-0">
        <div class="px-5 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-tecsisa-yellow/10 flex items-center justify-center border border-tecsisa-yellow/20">
                    <span class="text-tecsisa-yellow font-black text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div>
                    <h1 class="text-xs text-gray-400 font-bold uppercase tracking-widest">Técnico Nivel 1</h1>
                    <p class="text-sm font-black text-white leading-tight">{{ explode(' ', Auth::user()->name)[0] }}</p>
                </div>
            </div>
            
            <button class="relative p-2 bg-white/5 rounded-full border border-white/10 text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <div class="absolute top-1.5 right-1.5 w-2 h-2 bg-tecsisa-yellow rounded-full shadow-[0_0_5px_rgba(255,209,0,1)]"></div>
            </button>
        </div>
    </header>

    <!-- Contenido Scrollable -->
    <main class="flex-1 overflow-y-auto no-scrollbar relative z-0 pb-safe">
        {{ $slot }}
    </main>

    <!-- Bottom Navigation Bar (App Nativa Flow) -->
    <nav class="bg-[#0f1217]/95 backdrop-blur-2xl border-t border-white/10 shrink-0 pb-safe-bottom z-50">
        <div class="flex justify-around items-center px-2 py-3">
            <a href="{{ route('technician.dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('technician.dashboard') ? 'text-tecsisa-yellow' : 'text-gray-500 hover:text-gray-400' }} transition-colors">
                <svg class="w-6 h-6 outline-none" fill="{{ request()->routeIs('technician.dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-[10px] font-bold uppercase tracking-widest">Tareas</span>
            </a>
            
            <a href="#" class="flex flex-col items-center gap-1 text-gray-500 hover:text-gray-400 transition-colors">
                <div class="relative"> <!-- Escáner central flotante -->
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 w-12 h-12 bg-tecsisa-yellow rounded-full shadow-[0_5px_20px_rgba(255,209,0,0.4)] flex items-center justify-center text-black border-4 border-[#05080f]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-widest mt-6 opacity-0">Escanear</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="flex">
                @csrf
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); this.closest('form').submit();"
                   class="flex flex-col items-center gap-1 text-gray-500 hover:text-gray-400 transition-colors">
                    <svg class="w-6 h-6 outline-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Salir</span>
                </a>
            </form>
        </div>
    </nav>
</body>
</html>
