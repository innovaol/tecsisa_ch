<x-app-layout>
    <div class="py-6 md:py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
        <!-- Header: Tarjeta Propia -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 mb-6 transition-all duration-500 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-tecsisa-yellow/5 rounded-full blur-2xl"></div>
            <h2 class="text-3xl font-black transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                Panel de <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">{{ Auth::user()->hasRole('Administrador') ? 'Control Global' : 'Mis Actividades' }}</span>
            </h2>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-2 px-1">
                {{ Auth::user()->hasRole('Administrador') ? 'Estado en tiempo real de la infraestructura hospitalaria' : 'Resumen de operatividad y tareas asignadas' }}
            </p>
        </div>
            
            <!-- KPIs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8">
                <!-- Card 1 -->
                <div class="bg-theme-card backdrop-blur-md rounded-2xl border border-theme hover:border-tecsisa-yellow/50 transition duration-300 group">
                    <div class="p-4 md:p-5">
                        <div class="flex items-center justify-between">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider transition" :class="theme === 'light' ? 'group-hover:text-slate-900' : 'group-hover:text-white'">{{ Auth::user()->hasRole('Administrador') ? 'Equipos Operativos' : 'Reportes Finalizados' }}</div>
                            <div class="p-1.5 bg-green-500/10 rounded-lg text-green-400 border border-green-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-2 flex items-baseline text-3xl font-black transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                            {{ $equipos_operativos }}
                            <span class="ml-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ Auth::user()->hasRole('Administrador') ? 'unidades' : 'tareas' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-theme-card backdrop-blur-md rounded-2xl border border-theme hover:border-tecsisa-yellow/50 transition duration-300 group">
                    <div class="p-4 md:p-5">
                        <div class="flex items-center justify-between">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider transition" :class="theme === 'light' ? 'group-hover:text-slate-900' : 'group-hover:text-white'">Intervenciones</div>
                            <div class="p-1.5 bg-yellow-500/10 rounded-lg text-tecsisa-yellow border border-yellow-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-2 flex items-baseline text-3xl font-black transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                            {{ $trabajos_pendientes }}
                            <span class="ml-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">en proceso</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-theme-card backdrop-blur-md rounded-2xl border border-theme hover:border-tecsisa-yellow/50 transition duration-300 group">
                    <div class="p-4 md:p-5">
                        <div class="flex items-center justify-between">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider transition" :class="theme === 'light' ? 'group-hover:text-slate-900' : 'group-hover:text-white'">{{ Auth::user()->hasRole('Administrador') ? 'Infraestructura Pasiva' : 'Total Asignaciones' }}</div>
                            <div class="p-1.5 bg-blue-500/10 rounded-lg text-blue-400 border border-blue-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-2 flex items-baseline text-3xl font-black transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                            {{ $cable_instalado }}<span class="text-xs text-gray-500 ml-1 font-bold uppercase tracking-widest tracking-tighter">{{ Auth::user()->hasRole('Administrador') ? 'm lineal' : 'tickets' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Panel: Actividad Reciente -->
            <div class="bg-theme-card border border-theme rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
                <div class="px-8 py-6 border-b border-theme flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors duration-500" :class="theme === 'light' ? 'bg-theme-card' : 'bg-white/5'">
                    <h3 class="text-xs font-black transition-colors duration-500 uppercase tracking-[0.2em] flex items-center gap-3" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                        <span class="w-2 h-2 rounded-full bg-tecsisa-yellow shadow-[0_0_10px_rgba(255,209,0,0.5)]"></span>
                        {{ Auth::user()->hasRole('Administrador') ? 'Actividad Reciente del Sistema' : 'Mis Últimas Intervenciones' }}
                    </h3>
                    <div class="flex gap-2 w-full sm:w-auto">
                        @if(Auth::user()->hasRole('Administrador'))
                        <a href="{{ route('reports.weekly.index') }}" class="flex-1 sm:flex-none text-[9px] font-black text-emerald-500 transition uppercase tracking-widest border border-emerald-500/20 bg-emerald-500/5 hover:bg-emerald-500 px-4 py-2.5 rounded-xl flex items-center justify-center gap-2" :class="theme === 'light' ? 'hover:text-white' : 'hover:text-white'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Reporte Semanal
                        </a>
                        @endif
                        <a href="{{ route('tasks.index') }}" class="flex-1 sm:flex-none text-[9px] font-black text-tecsisa-yellow hover:text-black transition uppercase tracking-widest border border-tecsisa-yellow/20 bg-tecsisa-yellow/5 hover:bg-tecsisa-yellow px-4 py-2.5 rounded-xl flex items-center justify-center">
                            {{ Auth::user()->hasRole('Administrador') ? 'Explorar Historial' : 'Mi Hoja de Ruta' }}
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    @if($equipos_operativos > 0 || $trabajos_pendientes > 0)
                        <div class="overflow-hidden w-full rounded-2xl border border-theme bg-black/5 dark:bg-white/5">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="text-[10px] font-black uppercase tracking-[0.2em] border-b border-theme transition-colors duration-500" style="color: var(--theme-text-muted)" :class="theme === 'light' ? 'bg-theme-card' : 'bg-white/5'">
                                            <th class="px-8 py-5">Equipo</th>
                                            <th class="px-6 py-5">Ubicación</th>
                                            <th class="px-6 py-5">Intervención</th>
                                            <th class="px-8 py-5 text-right">Tiempos</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y border-theme transition-colors duration-500">
                                        @foreach($recent_activity as $act)
                                        <tr class="text-xs transition-colors duration-500 border-b border-theme last:border-0" style="color: var(--theme-text)" onmouseenter="this.style.backgroundColor = 'var(--theme-table-row-hover)'" onmouseleave="this.style.backgroundColor = 'transparent'">
                                            <td class="px-8 py-5 font-black text-tecsisa-yellow uppercase tracking-widest">{{ $act->equipment->internal_id ?? 'SQL' }}</td>
                                            <td class="px-6 py-5 font-black text-[10px] uppercase transition-colors duration-500" style="color: var(--theme-text)">{{ $act->location_snapshot ?? 'CH-00' }}</td>
                                            <td class="px-6 py-5">
                                                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border
                                                    {{ $act->status == 'completed' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-tecsisa-yellow/10 text-tecsisa-yellow border-tecsisa-yellow/20' }}">
                                                    {{ $act->title }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-5 text-right font-bold transition-colors duration-500 uppercase text-[9px] tracking-widest" style="color: var(--theme-text-muted)">{{ $act->updated_at->diffForHumans() }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Removed BDR button per user request --}}
                    @else
                        <div class="py-12 flex flex-col items-center">
                            <div class="w-14 h-14 bg-black/5 dark:bg-white/5 rounded-2xl flex items-center justify-center border border-theme mb-4">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <p class="text-theme font-bold text-base uppercase tracking-widest">Sin datos registrados</p>
                            @if(Auth::user()->hasRole('Administrador'))
                            <a href="{{ route('catalog.index') }}" class="mt-4 px-6 py-2 bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark rounded-xl text-[10px] font-black uppercase tracking-widest transition shadow-xl">
                                + Añadir Equipo
                            </a>
                            @else
                            <p class="text-xs text-gray-500 mt-2 uppercase font-black tracking-widest">Contacte al supervisor para asignar tareas</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
    </div>
</x-app-layout>
