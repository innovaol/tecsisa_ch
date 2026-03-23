<x-technician-layout :hideHeader="true" :hideNav="false">
    <!-- Top Header (Contextual) -->
    <div class="fixed top-0 inset-x-0 z-[60] bg-theme-header backdrop-blur-xl border-b border-theme pt-safe">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('technician.scanner') }}" class="w-11 h-11 flex items-center justify-center bg-theme/5 border border-theme text-theme-muted hover:text-tecsisa-yellow rounded-2xl transition shadow-md active:scale-90 group shrink-0">
                        <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div class="flex flex-col">
                        <span class="text-[8px] font-black text-tecsisa-yellow uppercase tracking-[0.3em] leading-none mb-1">Identificar Activo</span>
                        <h1 class="text-xs font-black uppercase tracking-wider flex items-center gap-2" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                             {{ $equipment->internal_id }}
                        </h1>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    @if(Auth::user()->hasRole('Administrador'))
                    <a href="{{ route('catalog.index') }}?equipment_id={{ $equipment->id }}" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-tecsisa-yellow/10 border border-tecsisa-yellow/20 text-[10px] font-black text-tecsisa-yellow uppercase hover:bg-tecsisa-yellow hover:text-black transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Editar Activo
                    </a>
                    @endif
                    <div class="w-2.5 h-2.5 rounded-full {{ $equipment->status === 'operative' ? 'bg-emerald-500 animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.5)]' : 'bg-red-500' }}"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT COLUMN: Main Info & Specs (8/12) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Primary Identity Card -->
                <div class="bg-theme-card rounded-[2.5rem] border border-theme p-8 shadow-2xl relative overflow-hidden transition-colors duration-500">
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-tecsisa-yellow/5 rounded-full blur-[100px] pointer-events-none"></div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-start gap-6">
                        <div class="w-20 h-20 bg-black/10 border border-theme rounded-3xl flex items-center justify-center shadow-inner shrink-0 scale-110 sm:scale-100 transition-colors duration-500">
                            <svg class="w-10 h-10 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="bg-tecsisa-yellow text-black text-[9px] font-black px-2 py-0.5 rounded tracking-widest uppercase shadow-lg shadow-yellow-400/10">{{ $equipment->system->name ?? 'SISTEMA' }}</span>
                                <span class="bg-black/5 border border-theme text-gray-500 text-[9px] font-black px-2 py-0.5 rounded tracking-widest uppercase">SERIAL: {{ $equipment->serial_number ?? 'N/A' }}</span>
                            </div>
                            <h2 class="text-3xl md:text-4xl font-black leading-tight mb-4 tracking-tight transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $equipment->name }}</h2>
                            
                            <div class="flex flex-wrap items-center gap-4 text-xs font-bold text-gray-400">
                                <div class="flex items-center gap-2 bg-black/5 px-3 py-1.5 rounded-full border border-theme transition-colors duration-500">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    <span :class="theme === 'light' ? 'text-slate-600' : 'text-gray-400'">{{ $equipment->location ? $equipment->location->name : 'Área No Asignada' }}</span>
                                </div>
                                <div class="flex items-center gap-2 opacity-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Capturado {{ $equipment->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extended Specs -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.4em]" :class="theme === 'light' ? 'text-slate-400' : 'text-gray-500'">Ficha Técnica Detallada</h3>
                        <div class="h-px bg-theme border-t border-theme flex-1 ml-6"></div>
                    </div>
                    
                    <div class="bg-theme-card border border-theme rounded-[2rem] p-6 sm:p-8 transition-colors duration-500">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
                            @if(is_array($equipment->specs) && count($equipment->specs) > 0)
                                @foreach($equipment->specs as $key => $value)
                                    <div class="group">
                                        <p class="text-[8px] text-gray-500 font-black uppercase tracking-widest mb-1.5 group-hover:text-tecsisa-yellow transition-colors">{{ str_replace('_', ' ', $key) }}</p>
                                        <div class="bg-black/5 border border-theme rounded-2xl p-4 transition group-hover:border-tecsisa-yellow/20">
                                            <p class="text-xs font-mono font-bold break-all leading-relaxed transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ is_array($value) ? implode(', ', $value) : $value }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-span-full py-10 flex flex-col items-center opacity-30">
                                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-[10px] font-black uppercase tracking-widest">Sin parámetros registrados</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Actions (4/12) -->
            <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-6">
                 <div class="bg-theme-card rounded-[2.5rem] border border-theme p-8 shadow-2xl relative transition-all duration-500 overflow-hidden">
                    <div class="absolute -right-20 -bottom-20 w-48 h-48 bg-tecsisa-yellow/5 rounded-full blur-3xl pointer-events-none"></div>
                    <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-6 text-center" :class="theme === 'light' ? 'text-slate-400' : 'text-gray-500'">Protocolo de Intervención</h3>
                    
                    <div class="space-y-4 relative z-10">
                        <!-- Botón Mantenimiento -->
                        <form action="{{ route('tasks.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                            <input type="hidden" name="title" value="Mantenimiento de Equipo">
                            <input type="hidden" name="priority" value="medium">
                            <input type="hidden" name="task_type" value="Mantenimiento">
                            <input type="hidden" name="description" value="Procedimiento de mantenimiento preventivo y/o correctivo.">
                            <button type="submit" class="w-full group flex items-center gap-4 bg-black/5 hover:bg-tecsisa-yellow border border-theme hover:border-tecsisa-yellow rounded-2xl p-4 transition-all active:scale-95">
                                <div class="w-12 h-12 bg-black/5 rounded-xl flex items-center justify-center text-tecsisa-yellow group-hover:text-black transition-colors duration-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-[10px] font-black uppercase leading-tight group-hover:text-black transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Mantenimiento</p>
                                    <p class="text-[8px] font-bold text-gray-500 group-hover:text-black/60 uppercase mt-0.5">Preventivo / Limpieza</p>
                                </div>
                            </button>
                        </form>

                        <!-- Botón Instalación -->
                        <form action="{{ route('tasks.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                            <input type="hidden" name="title" value="Instalación / Configuración">
                            <input type="hidden" name="priority" value="medium">
                            <input type="hidden" name="task_type" value="Instalación">
                            <input type="hidden" name="description" value="Procedimiento de instalación y puesta a punto de un nuevo activo.">
                            <button type="submit" class="w-full group flex items-center gap-4 bg-black/5 hover:bg-cyan-500 border border-theme hover:border-cyan-400 rounded-2xl p-4 transition-all active:scale-95">
                                <div class="w-12 h-12 bg-black/5 rounded-xl flex items-center justify-center text-cyan-400 group-hover:text-white transition-colors duration-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-[10px] font-black uppercase leading-tight group-hover:text-white transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Instalación</p>
                                    <p class="text-[8px] font-bold text-gray-500 group-hover:text-white/80 uppercase mt-0.5">Configuración Inicial</p>
                                </div>
                            </button>
                        </form>

                        <!-- Botón Reemplazo -->
                        <form action="{{ route('tasks.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                            <input type="hidden" name="title" value="Sustitución / Reemplazo Físico">
                            <input type="hidden" name="priority" value="high">
                            <input type="hidden" name="task_type" value="Sustitución">
                            <input type="hidden" name="description" value="Procedimiento de extracción y cambio de equipo por otro.">
                            <button type="submit" class="w-full group flex items-center gap-4 bg-black/5 hover:bg-red-500 border border-theme hover:border-red-400 rounded-2xl p-4 transition-all active:scale-95">
                                <div class="w-12 h-12 bg-black/5 rounded-xl flex items-center justify-center text-red-400 group-hover:text-white transition-colors duration-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-[10px] font-black uppercase leading-tight group-hover:text-white transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Sustitución</p>
                                    <p class="text-[8px] font-bold text-gray-500 group-hover:text-white/80 uppercase mt-0.5">Cambio de Activo Físico</p>
                                </div>
                            </button>
                        </form>
                    </div>

                    <div class="mt-8 pt-6 border-t border-theme">
                        <a href="{{ route('technician.scanner') }}" class="flex items-center justify-center gap-2 text-gray-500 text-[10px] font-black uppercase tracking-[0.2em] transition group" :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'">
                            <svg class="w-3.5 h-3.5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                            Volver al Buscador
                        </a>
                    </div>
                 </div>

                 <!-- Security Notice -->
                 <div class="px-6 py-4 bg-blue-500/5 rounded-3xl border border-blue-500/10">
                    <p class="text-[8px] text-blue-400 font-bold uppercase tracking-wider leading-relaxed">
                        ⚠️ Al iniciar un reporte, se capturará tu ubicación actual, fecha y hora bajo el protocolo AUDIT-TRUCK de Tecsisa.
                    </p>
                 </div>
            </div>
        </div>
    </div>
</x-technician-layout>
