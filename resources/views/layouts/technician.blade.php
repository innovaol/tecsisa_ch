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
    @include('layouts.navigation', ['hideHeader' => $hideHeader ?? false])

    <!-- Contenido Principal -->
    <main class="flex-1 md:overflow-visible overflow-y-auto no-scrollbar relative z-10 {{ ($hideNav ?? false) ? '' : 'pb-28 md:pb-0' }}">
        <div class="md:max-w-7xl md:mx-auto md:w-full md:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

    @stack('scripts')
</body>
</html>
