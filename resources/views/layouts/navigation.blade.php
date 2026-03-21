<div x-data="{ open: false }">
    <nav class="bg-theme-header backdrop-blur-xl border-b border-theme shadow-lg sticky top-0 md:relative z-[100] transition-colors duration-500">
        <!-- Desktop Navigation Menu (md and up) -->
        <div class="hidden md:block max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo class="block h-10 w-auto text-tecsisa-yellow" />
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8 h-full">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="h-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            {{ __('Inicio') }}
                        </x-nav-link>

                        @if(Auth::user()->hasRole('Administrador'))
                        <x-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')" class="h-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            {{ __('Catálogo') }}
                        </x-nav-link>

                        <x-nav-link :href="route('rack.builder')" :active="request()->routeIs('rack.*')" class="h-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                            {{ __('Racks') }}
                        </x-nav-link>
                        @else
                        <x-nav-link :href="route('technician.scanner')" :active="request()->routeIs('technician.scanner') || request()->routeIs('technician.equipment.*')" class="h-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            {{ __('Buscador') }}
                        </x-nav-link>
                        @endif

                        <!-- Tareas (Direct access) -->
                        <x-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')" class="h-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            {{ __('Tareas') }}
                        </x-nav-link>

                        @if(Auth::user()->hasRole('Administrador'))
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index')" class="h-full flex items-center gap-2">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            {{ __('Reportes') }}
                        </x-nav-link>

                        <!-- Usuarios (Direct access for Admin) -->
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="h-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            {{ __('Usuarios') }}
                        </x-nav-link>
                        @endif
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden md:flex md:items-center md:ms-6 gap-4">
                    <!-- Theme Toggle -->
                    <button @click="toggleTheme()" class="p-2 text-gray-400 hover:text-tecsisa-yellow transition-colors duration-300 rounded-lg hover:bg-white/5" title="Cambiar Tema">
                        <svg x-show="theme === 'dark'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l.707.707M6.343 6.343l.707-.707M14.5 12a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                        <svg x-show="theme === 'light'" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    <button class="p-2 text-gray-400 transition group relative" :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'">
                        <svg class="w-6 h-6 outline-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </button>
                    
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-theme text-sm leading-4 font-medium rounded-xl text-theme bg-theme-border/5 hover:bg-theme-border/10 focus:outline-none transition ease-in-out duration-150 backdrop-blur-sm shadow-xl">
                                <div class="w-7 h-7 rounded-full bg-tecsisa-yellow/20 flex items-center justify-center text-tecsisa-yellow mr-3 border border-tecsisa-yellow/30 font-black text-xs uppercase">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="font-bold">{{ Auth::user()->name }}</div>
                                <div class="ms-2 text-tecsisa-yellow">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Mi Perfil') }}</x-dropdown-link>
                            @if(Auth::user()->hasRole('Administrador'))
                                <x-dropdown-link :href="route('settings.index')">{{ __('Configuración Empresa') }}</x-dropdown-link>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); if(confirm('¿Estás seguro de que deseas cerrar sesión?')) this.closest('form').submit();" class="text-red-400 font-bold">{{ __('Cerrar Sesión') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>

        @if(!($hideHeader ?? false))
        <!-- Mobile Header (below md) -->
        <div class="md:hidden px-5 py-4 flex justify-between items-center bg-theme-header backdrop-blur-xl border-b border-theme transition-colors duration-500">
            <div class="flex items-center gap-3">
                <button @click="toggleTheme()" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 active:scale-90 transition shadow-inner">
                    <svg x-show="theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l.707.707M6.343 6.343l.707-.707M14.5 12a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                    <svg x-show="theme === 'light'" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>

                <div class="w-10 h-10 rounded-full bg-tecsisa-yellow/10 flex items-center justify-center border border-tecsisa-yellow/20">
                    <span class="text-tecsisa-yellow font-black text-lg uppercase">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div>
                    <h1 class="text-[10px] font-black uppercase tracking-widest leading-none text-theme-muted transition-colors duration-500">{{ Auth::user()->hasRole('Administrador') ? 'Administrador' : 'Técnico Especialista' }}</h1>
                    <p class="text-sm font-black leading-tight mt-1 transition-colors duration-500" :class="theme === 'light' ? 'text-black' : 'text-white'">{{ explode(' ', Auth::user()->name)[0] }}</p>
                </div>
            </div>
            
            <button @click="open = !open" class="p-2.5 bg-white/5 rounded-xl border border-white/10 text-gray-400 active:scale-95 transition">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @endif

        <!-- Responsive Drawer (For Profile/Extra Actions on Mobile) -->
        <div x-show="open" style="display: none;" class="md:hidden bg-theme-card p-6 shadow-2xl relative z-[150] border-b border-theme transition-colors duration-500" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0 text-theme">
            <div class="space-y-4">
                 <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-3 py-4 border-b border-theme transition-colors duration-500">
                    <svg class="w-5 h-5 text-theme-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="font-bold text-theme">Mi Perfil</span>
                 </x-dropdown-link>
 
                  @if(Auth::user()->hasRole('Administrador'))
                  <x-dropdown-link :href="route('settings.index')" class="flex items-center gap-3 py-4 border-b border-theme transition-colors duration-500">
                     <svg class="w-5 h-5 text-theme-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                     <span class="font-bold text-theme">Configuración Empresa</span>
                  </x-dropdown-link>
                  @endif
 
                  <div class="pt-4 border-t border-theme">
                     <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); if(confirm('¿Cerrar sesión ahora?')) this.closest('form').submit();" class="text-red-400 flex items-center gap-3 py-4 font-black uppercase text-xs tracking-widest">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span>Cerrar Sesión</span>
                        </x-dropdown-link>
                     </form>
                  </div>
            </div>
        </div>
    </nav>

    @if(!($hideNav ?? false))
    <!-- Mobile Bottom Navigation (Always hidden on md+) -->
    <div class="md:hidden">
        <div class="fixed bottom-0 inset-x-0 bg-theme-header backdrop-blur-3xl border-t border-theme z-[100] pb-safe-bottom shadow-[0_-10px_30px_rgba(0,0,0,0.5)] transition-colors duration-500">
            <div class="flex justify-around items-center py-3 px-2">
                <!-- Item 1: Inicio -->
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-tecsisa-yellow' : '' }} transition-colors" style="color: {{ request()->routeIs('dashboard') ? '#FFD100' : 'var(--theme-nav-inactive)' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-[9px] font-black uppercase tracking-tighter">Inicio</span>
                </a>

                @if(Auth::user()->hasRole('Administrador'))
                    <!-- Item 2: Inventario para Admins -->
                    <a href="{{ route('catalog.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('catalog.*') ? 'text-tecsisa-yellow' : '' }} transition-colors text-center" style="color: {{ request()->routeIs('catalog.*') ? '#FFD100' : 'var(--theme-nav-inactive)' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                        <span class="text-[9px] font-black uppercase tracking-tighter">Catálogo</span>
                    </a>

                    <!-- Item Racks para Admins (Mobile) -->
                    <a href="{{ route('rack.builder') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('rack.*') ? 'text-tecsisa-yellow' : '' }} transition-colors text-center" style="color: {{ request()->routeIs('rack.*') ? '#FFD100' : 'var(--theme-nav-inactive)' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                        <span class="text-[9px] font-black uppercase tracking-tighter">Racks</span>
                    </a>
                @else
                    <!-- Item 2: Catálogos para Técnicos (Equivalente al Admin) -->
                    <a href="{{ route('technician.equipment.list') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('technician.equipment.list') ? 'text-tecsisa-yellow' : '' }} transition-colors" style="color: {{ request()->routeIs('technician.equipment.list') ? '#FFD100' : 'var(--theme-nav-inactive)' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                        <span class="text-[9px] font-black uppercase tracking-tighter">Equipos</span>
                    </a>
                @endif

                <!-- Item 3: Buscador (Central) - Solo Móviles Verdaderos -->
                <div class="md:hidden">
                    <a href="{{ route('technician.scanner') }}" class="flex flex-col items-center gap-1 text-theme-muted focus:outline-none relative">
                        <div class="absolute -top-7 left-1/2 transform -translate-x-1/2 w-14 h-14 bg-tecsisa-yellow rounded-full shadow-[0_8px_30px_rgba(255,209,0,0.5)] flex items-center justify-center text-black border-4 border-theme active:scale-90 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-tighter mt-8">Buscador</span>
                    </a>
                </div>

                <!-- Item 4: Tareas (Shared) -->
                <a href="{{ route('tasks.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('tasks.*') ? 'text-tecsisa-yellow' : '' }} transition-colors" style="color: {{ request()->routeIs('tasks.*') ? '#FFD100' : 'var(--theme-nav-inactive)' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="text-[9px] font-black uppercase tracking-tighter">Tareas</span>
                </a>

                @if(Auth::user()->hasRole('Administrador'))
                <!-- Item 5: Usuarios (Admin Only) -->
                <a href="{{ route('users.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('users.*') ? 'text-tecsisa-yellow' : '' }} transition-colors text-center" style="color: {{ request()->routeIs('users.*') ? '#FFD100' : 'var(--theme-nav-inactive)' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="text-[9px] font-black uppercase tracking-tighter">Usuarios</span>
                </a>
                @endif

                @if(Auth::user()->hasRole('Administrador'))
                <!-- Item 6: Reportes (Replaces Logout in mobile) -->
                <a href="{{ route('reports.index') }}" class="flex flex-col items-center gap-1 transition-colors" style="color: {{ request()->routeIs('reports.index') ? '#FFD100' : 'var(--theme-nav-inactive)' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="text-[9px] font-black uppercase tracking-tighter">Reportes</span>
                </a>
                @else
                <!-- Item 6: Salir (Restore for technicians) -->
                <form method="POST" action="{{ route('logout') }}" class="flex">
                    @csrf
                    <button type="submit" onclick="event.preventDefault(); if(confirm('¿Deseas cerrar sesión?')) this.closest('form').submit();" class="flex flex-col items-center gap-1 transition-colors" style="color: var(--theme-nav-inactive)">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="text-[10px] font-black uppercase tracking-tighter">Salir</span>
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>
    @endif
</div>
