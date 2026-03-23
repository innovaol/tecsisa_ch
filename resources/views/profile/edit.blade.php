<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
        <!-- Profile Header -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-10 mb-6 transition-all duration-500 shadow-xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-tecsisa-yellow/5 rounded-full blur-3xl"></div>
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="p-3 bg-theme/5 border border-theme text-theme-muted hover:text-tecsisa-yellow rounded-2xl transition-all shadow-md active:scale-95 group">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="text-3xl font-black transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                        Seguridad de <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Mi Perfil</span>
                    </h2>
                    <p class="text-xs text-theme-muted font-bold uppercase tracking-widest mt-2 px-1">Gestión de contraseña y protección de cuenta</p>
                </div>
            </div>
        </div>

        <!-- Identity Summary (Read Only) -->
        <div class="bg-theme-card rounded-[2.5rem] border border-theme p-10 mb-8 shadow-2xl relative overflow-hidden group transition-all duration-500">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-tecsisa-yellow/5 rounded-full blur-[120px] pointer-events-none group-hover:bg-tecsisa-yellow/10 transition-colors duration-700"></div>
            
            <div class="flex flex-col md:flex-row items-center gap-10 relative z-10">
                <div class="relative">
                    <div class="w-32 h-32 rounded-[2rem] bg-black/10 flex items-center justify-center text-tecsisa-yellow border border-theme font-black text-5xl shadow-inner transition-colors duration-500">
                         {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-emerald-500 border-4 rounded-xl shadow-lg transition-all duration-500" :style="theme === 'light' ? 'border-color: #fff' : 'border-color: #1a1f2e'"></div>
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
                    <h1 class="text-3xl md:text-4xl font-black leading-tight mb-2 tracking-tight transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ Auth::user()->name }}</h1>
                    <p class="text-gray-500 font-bold uppercase tracking-widest text-[11px] flex items-center justify-center md:justify-start gap-2">
                         <svg class="w-4 h-4 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                         {{ Auth::user()->email }}
                    </p>
                </div>
                
                <div class="w-full md:w-auto">
                    <div class="bg-blue-500/5 border border-blue-500/10 p-5 rounded-2xl max-w-xs">
                        <p class="text-[9px] text-blue-400 font-black uppercase tracking-widest leading-relaxed">
                            ⚠️ Los datos de identidad y cuenta están bloqueados por administración. Para cambios, contacte al supervisor.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Password Form (Center) -->
            <div class="lg:col-span-7">
                <div class="bg-theme-card border border-theme p-10 rounded-[2.5rem] shadow-2xl transition-colors duration-500">
                    <h3 class="text-xs font-black text-blue-500 uppercase tracking-[0.3em] mb-10 flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        Cambiar Contraseña de Acceso
                    </h3>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Stats (Sidebar) -->
            <div class="lg:col-span-5">
                <div class="bg-theme-card border border-theme p-10 rounded-[2.5rem] shadow-2xl transition-colors duration-500">
                    <h3 class="text-xs font-black text-emerald-500 uppercase tracking-[0.3em] mb-10 flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Resumen de Actividad
                    </h3>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-theme/5 p-6 rounded-3xl border border-theme transition-colors duration-500 flex justify-between items-center">
                            <p class="text-[10px] font-black text-theme-muted uppercase tracking-widest">Tareas Totales</p>
                            <p class="text-2xl font-black transition-colors duration-500 text-theme">{{ \App\Models\Task::where('assigned_to', Auth::id())->count() }}</p>
                        </div>
                        <div class="bg-theme/5 p-6 rounded-3xl border border-theme transition-colors duration-500 flex justify-between items-center">
                            <p class="text-[10px] font-black text-theme-muted uppercase tracking-widest">Completadas</p>
                            <p class="text-2xl font-black text-tecsisa-yellow">{{ \App\Models\Task::where('assigned_to', Auth::id())->where('status', 'completed')->count() }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-10 pt-8 border-t border-theme">
                        <p class="text-[9px] text-theme-muted font-bold uppercase tracking-tighter leading-relaxed">
                            Tu historial técnico se mantiene bajo el protocolo de auditoría Tecsisa. 
                            La eliminación de cuenta está restringida para preservar la trazabilidad de las intervenciones.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
