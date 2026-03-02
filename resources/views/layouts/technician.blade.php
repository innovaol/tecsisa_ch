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
<body class="font-sans antialiased bg-[#05080f] text-white md:overflow-auto md:h-auto overflow-hidden h-[100dvh]" style="display: flex; flex-direction: column;">
    
    <!-- Background Glowing Orbs (Premium Aesthetic) -->
    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-tecsisa-yellow/5 blur-[120px] pointer-events-none z-0"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[30%] h-[30%] rounded-full bg-blue-500/5 blur-[120px] pointer-events-none z-0"></div>

    <!-- Unified Master Navigation -->
    @unless($hideNav ?? false)
        @include('layouts.navigation', ['hideHeader' => $hideHeader ?? false])
    @endunless

    <!-- Contenido Principal -->
    <main class="flex-1 md:overflow-visible overflow-y-auto no-scrollbar relative z-10 {{ ($hideNav ?? false) ? '' : 'pb-28 md:pb-0' }}">
        <div class="md:max-w-7xl md:mx-auto md:w-full md:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>
</body>

    <!-- Modal Fondo Intento (Bottom Sheet) -->
    <div x-show="showScannerMenu" style="display: none;" class="fixed inset-0 z-[100] flex flex-col justify-end">
        <!-- Overlay oscuro -->
        <div x-show="showScannerMenu" @click="showScannerMenu = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Panel Deslizable -->
        <div x-show="showScannerMenu" class="bg-[#12161f] border-t border-white/10 rounded-t-3xl p-6 relative z-10 shadow-[0_-10px_40px_rgba(0,0,0,0.5)] transition-transform" x-transition:enter="ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
            <div class="w-12 h-1.5 bg-gray-600 rounded-full mx-auto mb-6"></div>
            <h3 class="text-xl font-bold text-white mb-2 text-center">Identificar Equipo</h3>
            <p class="text-xs text-gray-400 text-center mb-6 uppercase tracking-widest font-bold">Selecciona el método de ingreso</p>
            
            <div class="space-y-3">
                <a href="{{ route('technician.scanner') }}?mode=scan" class="w-full bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black py-4 rounded-xl text-sm uppercase tracking-widest flex items-center justify-center gap-3 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Escanear Código (Cámara)
                </a>
                <a href="{{ route('technician.scanner') }}?mode=text" class="w-full bg-transparent border-2 border-white/10 text-white hover:bg-white/5 font-bold py-4 rounded-xl text-sm uppercase tracking-widest flex items-center justify-center gap-3 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Ingreso Manual Predictivo
                </a>
                <a href="{{ route('technician.equipment.list') }}" class="w-full bg-[#1a202c] border border-white/5 text-gray-400 hover:text-white hover:bg-[#2d3748] font-bold py-4 rounded-xl text-sm uppercase tracking-widest flex items-center justify-center gap-3 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Explorar Catálogo Completo
                </a>
            </div>
            <button @click="showScannerMenu = false" class="mt-6 w-full text-center text-gray-500 font-bold text-xs uppercase tracking-widest pb-4 hover:text-white transition">Cancelar</button>
        </div>
    </div>
</body>
</html>
