<div x-data="{ open: false, showScannerMenu: false }">
    <nav class="bg-tecsisa-dark/80 backdrop-blur-xl border-b border-white/10 shadow-lg sticky top-0 md:relative z-[100] {{ ($hideHeader ?? false) ? 'hidden md:block' : '' }}">
        <!-- Desktop Navigation Menu (md and up) -->
        <div class="hidden md:block max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo class="block h-10 w-auto text-white" />
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8 h-full">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="h-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            {{ __('Inicio') }}
                        </x-nav-link>

                        <!-- Tareas (Direct access) -->
                        <x-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')" class="h-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            {{ __('Tareas') }}
                        </x-nav-link>

                        @if(Auth::user()->hasRole('Administrador'))
                        <!-- Usuarios (Direct access for Admin) -->
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="h-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            {{ __('Usuarios') }}
                        </x-nav-link>

                        <x-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')" class="h-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            {{ __('Catálogos') }}
                        </x-nav-link>

                        <x-nav-link :href="route('rack.builder')" :active="request()->routeIs('rack.*')" class="h-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                            {{ __('Racks') }}
                        </x-nav-link>
                        @else
                        <!-- Publicly visible Nav Links for Technicians on Desktop -->
                        <x-nav-link :href="route('technician.scanner')" :active="request()->routeIs('technician.scanner*')" class="h-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            {{ __('Buscar Equipos') }}
                        </x-nav-link>

                        <x-nav-link :href="route('rack.builder')" :active="request()->routeIs('rack.*')" class="h-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                            {{ __('Racks') }}
                        </x-nav-link>
                        @endif
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden md:flex md:items-center md:ms-6 gap-4">
                    <button class="p-2 text-gray-400 hover:text-white transition">
                        <svg class="w-6 h-6 outline-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </button>
                    
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-white/10 text-sm leading-4 font-medium rounded-xl text-gray-300 bg-white/5 hover:bg-white/10 hover:text-white focus:outline-none transition ease-in-out duration-150 backdrop-blur-sm shadow-xl">
                                <div class="w-7 h-7 rounded-full bg-tecsisa-yellow/20 flex items-center justify-center text-tecsisa-yellow mr-3 border border-tecsisa-yellow/30 font-black text-xs">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-2 text-tecsisa-yellow">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Mi Perfil') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Cerrar Sesión') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>

        <!-- Mobile Header (below md) -->
        <div class="md:hidden px-5 py-4 flex justify-between items-center bg-[#0f1217]/90 backdrop-blur-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-tecsisa-yellow/10 flex items-center justify-center border border-tecsisa-yellow/20">
                    <span class="text-tecsisa-yellow font-black text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div>
                    <h1 class="text-[10px] text-gray-500 font-black uppercase tracking-widest">{{ Auth::user()->hasRole('Administrador') ? 'Administrador' : 'Técnico Especialista' }}</h1>
                    <p class="text-sm font-black text-white leading-tight">{{ explode(' ', Auth::user()->name)[0] }}</p>
                </div>
            </div>
            
            <button @click="open = !open" class="p-2 bg-white/5 rounded-xl border border-white/10 text-gray-400">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Responsive Drawer (For Profile/Extra Actions on Mobile) -->
    <div x-show="open" style="display: none;" class="md:hidden bg-tecsisa-dark/95 backdrop-blur-3xl border-t border-white/10 p-6 shadow-2xl relative z-[150]">
        <div class="space-y-4">
             @if(Auth::user()->hasRole('Administrador'))
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="flex items-center gap-3 py-4 border-b border-white/5">
                    <div class="p-2 rounded-lg bg-emerald-500/10 text-emerald-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></div>
                    <div class="font-black text-white uppercase tracking-widest text-sm">Usuarios y Roles</div>
                </x-responsive-nav-link>
             @endif
             
             <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center gap-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="font-bold text-gray-300">Ajustes de Perfil</span>
             </x-responsive-nav-link>

             <div class="border-t border-white/5 pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-400 flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="font-bold">Desconectarse</span>
                    </x-responsive-nav-link>
                </form>
             </div>
        </div>
    </div>
    </nav>

    <!-- Mobile Bottom Navigation (Always hidden on md+) -->
    <div class="md:hidden">
        <div class="fixed bottom-0 inset-x-0 bg-[#0f1217]/98 backdrop-blur-3xl border-t border-white/10 z-[100] pb-safe-bottom shadow-[0_-10px_30px_rgba(0,0,0,0.5)]">
            <div class="flex justify-around items-center py-3 px-2">
                <!-- Item 1: Inicio -->
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-tecsisa-yellow' : 'text-gray-500 hover:text-gray-400' }} transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-[9px] font-black uppercase tracking-tighter">Inicio</span>
                </a>

                @if(Auth::user()->hasRole('Administrador'))
                    <!-- Item 2: Gestión (Hub) para Admins -->
                    <a href="{{ route('technician.infrastructure') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('technician.infrastructure') ? 'text-tecsisa-yellow' : 'text-gray-500 hover:text-gray-400' }} transition-colors text-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        <span class="text-[9px] font-black uppercase tracking-tighter">Gestión</span>
                    </a>
                @else
                    <!-- Item 2: Activos para Técnicos -->
                    <a href="{{ route('technician.equipment.list') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('technician.equipment.list') ? 'text-tecsisa-yellow' : 'text-gray-500 hover:text-gray-400' }} transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <span class="text-[9px] font-black uppercase tracking-tighter">Activos</span>
                    </a>
                @endif

                <!-- Item 3: Escaneo (Central) -->
                <button @click="showScannerMenu = true" class="flex flex-col items-center gap-1 text-gray-500 focus:outline-none relative">
                    <div class="absolute -top-7 left-1/2 transform -translate-x-1/2 w-14 h-14 bg-tecsisa-yellow rounded-full shadow-[0_8px_30px_rgba(255,209,0,0.5)] flex items-center justify-center text-black border-4 border-[#0f1217] active:scale-90 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-tighter mt-7">Escanear</span>
                </button>

                <!-- Item 4: Tareas -->
                <a href="{{ route('technician.dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('technician.dashboard') ? 'text-tecsisa-yellow' : 'text-gray-500 hover:text-gray-400' }} transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="text-[9px] font-black uppercase tracking-tighter">Tareas</span>
                </a>

                <!-- Item 5: Salir -->
                <form method="POST" action="{{ route('logout') }}" class="flex">
                    @csrf
                    <button type="submit" class="flex flex-col items-center gap-1 text-gray-500 hover:text-gray-400 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="text-[9px] font-black uppercase tracking-tighter">Salir</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Scanner Menu (Mobile Bottom Sheet) -->
        <div x-show="showScannerMenu" style="display: none;" class="fixed inset-0 z-[110] flex flex-col justify-end pointer-events-none">
            <div x-show="showScannerMenu" @click="showScannerMenu = false" class="absolute inset-0 bg-black/70 backdrop-blur-sm transition-opacity pointer-events-auto" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
            <div x-show="showScannerMenu" class="bg-[#12161f] border-t border-white/10 rounded-t-[2.5rem] p-8 relative z-[120] pointer-events-auto shadow-[0_-15px_50px_rgba(0,0,0,0.8)] transition-transform" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto mb-8"></div>
                <h3 class="text-2xl font-black text-white mb-2 text-center uppercase tracking-widest">Identificar Activo</h3>
                <p class="text-xs text-gray-500 text-center mb-10 uppercase font-bold tracking-[0.2em]">Selecciona el método de entrada</p>
                <div class="space-y-4">
                    <a href="{{ route('technician.scanner') }}?mode=scan" class="w-full bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black py-5 rounded-[1.25rem] text-sm uppercase tracking-[0.15em] flex items-center justify-center gap-4 transition shadow-2xl active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Escanear Código QR
                    </a>
                    <a href="{{ route('technician.scanner') }}?mode=text" class="w-full bg-white/5 border border-white/10 text-white font-bold py-5 rounded-[1.25rem] text-sm uppercase tracking-[0.1em] flex items-center justify-center gap-4 transition active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Ingreso Manual
                    </a>
                </div>
                <button @click="showScannerMenu = false" class="mt-8 w-full text-center text-gray-600 font-bold text-xs uppercase tracking-widest pb-6 hover:text-white transition">Cerrar</button>
            </div>
        </div>
    </div>
</div>
