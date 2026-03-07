    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
        <!-- Profile Header: Tarjeta Propia -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-10 mb-6 transition-all duration-500 shadow-xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-tecsisa-yellow/5 rounded-full blur-3xl"></div>
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="p-3 bg-theme/5 border border-theme text-theme-muted hover:text-tecsisa-yellow rounded-2xl transition-all shadow-md active:scale-95 group">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="text-3xl font-black transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                        Gestión de <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Mi Perfil</span>
                    </h2>
                    <p class="text-xs text-theme-muted font-bold uppercase tracking-widest mt-2 px-1">Configuración de identidad, seguridad y preferencias de interfaz</p>
                </div>
            </div>
        </div>
        <!-- Profile Identity Card -->
        <div class="bg-theme-card rounded-[2.5rem] border border-theme p-10 mb-8 shadow-2xl relative overflow-hidden group transition-all duration-500">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-tecsisa-yellow/5 rounded-full blur-[120px] pointer-events-none group-hover:bg-tecsisa-yellow/10 transition-colors duration-700"></div>
            
            <div class="flex flex-col md:flex-row items-center gap-10 relative z-10">
                <div class="relative">
                    <div class="w-40 h-40 rounded-[2.5rem] bg-black/10 flex items-center justify-center text-tecsisa-yellow border border-theme font-black text-6xl shadow-inner transition-colors duration-500">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-emerald-500 border-4 rounded-2xl shadow-lg transition-all duration-500" :style="theme === 'light' ? 'border-color: #fff' : 'border-color: #1a1f2e'"></div>
                </div>
                
                <div class="text-center md:text-left flex-1">
                    <div class="flex flex-wrap justify-center md:justify-start items-center gap-4 mb-4">
                        <span class="bg-tecsisa-yellow text-black text-[10px] font-black px-3 py-1.5 rounded-full tracking-widest uppercase shadow-lg shadow-yellow-400/10">
                            {{ Auth::user()->hasRole('Administrador') ? 'Administrador Core' : 'Técnico Especialista' }}
                        </span>
                        <span class="bg-theme-border border border-theme text-gray-500 text-[10px] font-black px-3 py-1.5 rounded-full tracking-widest uppercase transition-colors duration-500">
                            Activo desde {{ Auth::user()->created_at->format('M Y') }}
                        </span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black leading-tight mb-3 tracking-tight transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ Auth::user()->name }}</h1>
                    <div class="flex flex-col md:flex-row items-center gap-4 text-gray-500 font-bold uppercase tracking-widest text-[11px]">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ Auth::user()->email }}
                        </span>
                        <span class="hidden md:block w-1 h-1 rounded-full bg-theme"></span>
                        <span class="flex items-center gap-2 transition-colors duration-500" :class="theme === 'light' ? 'text-slate-400' : 'text-gray-600'">
                            ID TEC: #{{ str_pad(Auth::id(), 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col gap-4 w-full md:w-auto">
                    <button onclick="window.scrollTo({top: document.getElementById('update-info').offsetTop - 120, behavior: 'smooth'})" class="w-full bg-theme-border hover:bg-tecsisa-yellow hover:text-black border border-theme text-gray-500 font-black py-4 px-8 rounded-2xl text-[10px] uppercase tracking-[0.2em] transition-all duration-300 shadow-xl active:scale-95">
                        Editar Perfil
                    </button>
                    <a href="{{ route('dashboard') }}" class="w-full bg-transparent hover:bg-white/5 border border-theme text-gray-500 font-black py-4 px-8 rounded-2xl text-[10px] uppercase tracking-[0.2em] transition-all duration-300 text-center">
                        Mi Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column: Settings -->
            <div class="space-y-8">
                <div id="update-info" class="bg-theme-card border border-theme p-8 rounded-[2.5rem] shadow-2xl transition-colors duration-500">
                    <h3 class="text-xs font-black text-tecsisa-yellow uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-tecsisa-yellow"></span>
                        Información Personal
                    </h3>
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="bg-theme-card border border-theme p-8 rounded-[2.5rem] shadow-2xl transition-colors duration-500">
                    <h3 class="text-xs font-black text-blue-500 uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        Seguridad de la Cuenta
                    </h3>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Right Column: Stats & Danger -->
            <div class="space-y-8">
                <!-- Activity Summary -->
                <div class="bg-theme-card border border-theme p-8 rounded-[2.5rem] shadow-2xl transition-colors duration-500">
                    <h3 class="text-xs font-black text-emerald-500 uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Resumen de Actividad
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-theme/5 p-6 rounded-3xl border border-theme transition-colors duration-500">
                            <p class="text-[10px] font-black text-theme-muted uppercase tracking-widest mb-1">Tareas Totales</p>
                            <p class="text-3xl font-black transition-colors duration-500 text-theme">{{ \App\Models\Task::where('assigned_to', Auth::id())->count() }}</p>
                        </div>
                        <div class="bg-theme/5 p-6 rounded-3xl border border-theme transition-colors duration-500">
                            <p class="text-[10px] font-black text-theme-muted uppercase tracking-widest mb-1">Completadas</p>
                            <p class="text-3xl font-black text-tecsisa-yellow">{{ \App\Models\Task::where('assigned_to', Auth::id())->where('status', 'completed')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-theme-card border border-theme p-8 rounded-[2.5rem] shadow-2xl transition-colors duration-500">
                    <h3 class="text-xs font-black text-red-500 uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        Zona de Riesgo
                    </h3>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
