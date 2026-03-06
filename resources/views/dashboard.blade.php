<x-app-layout>
    <div class="py-6 md:py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="mb-8 px-1">
            <h2 class="text-2xl md:text-3xl font-black text-white leading-tight">Visión <span class="text-tecsisa-yellow uppercase tracking-widest text-xs font-black">{{ Auth::user()->hasRole('Administrador') ? 'Global' : 'Personal' }}</span></h2>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1 px-1">{{ Auth::user()->hasRole('Administrador') ? 'Estado de la infraestructura hospitalaria' : 'Mi resumen de operatividad y tareas' }}</p>
        </div>
            
            <!-- KPIs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8">
                <!-- Card 1 -->
                <div class="bg-tecsisa-card backdrop-blur-md rounded-2xl border border-white/10 hover:border-tecsisa-yellow/50 transition duration-300 group">
                    <div class="p-4 md:p-5">
                        <div class="flex items-center justify-between">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider group-hover:text-white transition">{{ Auth::user()->hasRole('Administrador') ? 'Equipos Operativos' : 'Reportes Finalizados' }}</div>
                            <div class="p-1.5 bg-green-500/10 rounded-lg text-green-400 border border-green-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-2 flex items-baseline text-3xl font-black text-white">
                            {{ $equipos_operativos }}
                            <span class="ml-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ Auth::user()->hasRole('Administrador') ? 'unidades' : 'tareas' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-tecsisa-card backdrop-blur-md rounded-2xl border border-white/10 hover:border-tecsisa-yellow/50 transition duration-300 group">
                    <div class="p-4 md:p-5">
                        <div class="flex items-center justify-between">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider group-hover:text-white transition">Intervenciones</div>
                            <div class="p-1.5 bg-yellow-500/10 rounded-lg text-tecsisa-yellow border border-yellow-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-2 flex items-baseline text-3xl font-black text-white">
                            {{ $trabajos_pendientes }}
                            <span class="ml-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">pendientes</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-tecsisa-card backdrop-blur-md rounded-2xl border border-white/10 hover:border-tecsisa-yellow/50 transition duration-300 group">
                    <div class="p-4 md:p-5">
                        <div class="flex items-center justify-between">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider group-hover:text-white transition">{{ Auth::user()->hasRole('Administrador') ? 'Infraestructura Pasiva' : 'Total Asignaciones' }}</div>
                            <div class="p-1.5 bg-blue-500/10 rounded-lg text-blue-400 border border-blue-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-2 flex items-baseline text-3xl font-black text-white">
                            {{ $cable_instalado }}<span class="text-xs text-gray-500 ml-1 font-bold uppercase tracking-widest tracking-tighter">{{ Auth::user()->hasRole('Administrador') ? 'm lineal' : 'tickets' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Panel -->
            <div class="bg-[#0f1217]/60 backdrop-blur-xl rounded-3xl border border-white/5 overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between bg-white/[0.02]">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-tecsisa-yellow animate-pulse"></span>
                        {{ Auth::user()->hasRole('Administrador') ? 'Última Actividad Registrada' : 'Mis Cambios Recientes' }}
                    </h3>
                    <div class="flex gap-2">
                        @if(Auth::user()->hasRole('Administrador'))
                        <a href="{{ route('reports.weekly.index') }}" class="text-[10px] font-black text-emerald-400 hover:text-emerald-300 transition uppercase tracking-widest border border-emerald-500/20 px-3 py-1.5 rounded-lg flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Reporte Semanal
                        </a>
                        @endif
                        <a href="{{ route('tasks.index') }}" class="text-[10px] font-black text-tecsisa-yellow hover:text-yellow-300 transition uppercase tracking-widest border border-tecsisa-yellow/20 px-3 py-1.5 rounded-lg">{{ Auth::user()->hasRole('Administrador') ? 'Ver Reportes' : 'Mi Hoja de Ruta' }}</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($equipos_operativos > 0 || $trabajos_pendientes > 0)
                        <div class="overflow-hidden w-full rounded-2xl border border-white/5 bg-black/40">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] border-b border-white/5">
                                            <th class="px-6 py-4">Equipo</th>
                                            <th class="px-6 py-4">Ubicación</th>
                                            <th class="px-6 py-4">Evento / Acción</th>
                                            <th class="px-6 py-4 text-right">Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5">
                                        @foreach($recent_activity as $act)
                                        <tr class="text-xs text-gray-300 hover:bg-white/[0.02] transition">
                                            <td class="px-6 py-4 font-bold text-tecsisa-yellow">{{ $act->equipment->internal_id ?? 'SQL' }}</td>
                                            <td class="px-6 py-4 font-medium">{{ $act->location_snapshot ?? 'CH-00' }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase 
                                                    {{ $act->status == 'completed' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-tecsisa-yellow/10 text-tecsisa-yellow' }}">
                                                    {{ $act->title }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right text-gray-500 font-bold">{{ $act->updated_at->diffForHumans() }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-center">
                           <a href="{{ Auth::user()->hasRole('Administrador') ? route('catalog.index') : route('tasks.index') }}" class="px-6 py-3 bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark rounded-xl shadow-lg text-[10px] font-black uppercase tracking-widest transition inline-flex items-center gap-3 transform hover:scale-105 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                {{ Auth::user()->hasRole('Administrador') ? 'Abrir Base de Datos de Red (BDR)' : 'Explorar Todas mis Tareas' }}
                           </a>
                        </div>
                    @else
                        <div class="py-12 flex flex-col items-center">
                            <div class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 mb-4">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <p class="text-gray-300 font-bold text-base uppercase tracking-widest">Sin datos registrados</p>
                            <a href="{{ route('catalog.index') }}" class="mt-4 px-6 py-2 bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark rounded-xl text-[10px] font-black uppercase tracking-widest transition">
                                + Añadir Equipo
                            </a>
                        </div>
                    @endif
                </div>
            </div>
    </div>
</x-app-layout>
