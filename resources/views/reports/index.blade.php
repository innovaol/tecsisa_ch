<x-app-layout>
    <div class="py-6 md:py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-6 sm:p-10 mb-6 sm:mb-10 transition-all duration-500 shadow-xl relative">
             <!-- Decorative Orbs (Clipped) -->
            <div class="absolute inset-0 overflow-hidden rounded-[2.5rem] pointer-events-none">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-tecsisa-yellow/10 rounded-full blur-2xl"></div>
            </div>
            <div class="flex items-center gap-4 sm:gap-6 relative z-10">
                <a href="{{ route('dashboard') }}" class="w-11 h-11 flex items-center justify-center bg-theme/5 border border-theme text-theme-muted hover:text-tecsisa-yellow rounded-2xl transition-all shadow-md active:scale-95 group shrink-0">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black transition-colors duration-500 leading-tight text-theme flex items-center gap-2">
                        <span>Reportes</span>
                        <div class="group relative inline-block">
                            <svg class="w-5 h-5 text-theme-muted cursor-help p-0.5 hover:text-tecsisa-yellow transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0114 0z"></path></svg>
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-3 w-64 p-4 bg-black/95 text-[11px] text-white rounded-2xl opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-[100] border border-theme shadow-2xl normal-case font-bold backdrop-blur-md">
                                <div class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-3 h-3 bg-black/95 border-b border-r border-theme rotate-45"></div>
                                Consulta y gestión de información operativa del sistema.
                            </div>
                        </div>
                    </h2>
                    <p class="text-[10px] sm:text-xs text-theme-muted font-bold uppercase tracking-widest mt-1 sm:mt-2 px-1">Gestión de información operativa</p>
                </div>
            </div>
        </div>

        <!-- Filtros de Búsqueda Ultra Compacto y Simétrico -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-4 shadow-2xl transition-all duration-500">
            <form method="GET" action="{{ route('reports.index') }}" id="filter-form"
                  x-data="{ 
                    preset: '{{ request('preset_week') ?: (request('start_date') ? '' : 'current') }}',
                    isClearing: false,
                    clearDates() {
                        this.isClearing = true;
                        const start = document.getElementById('start_date');
                        const end = document.getElementById('end_date');
                        if(start && start._flatpickr) start._flatpickr.clear();
                        if(end && end._flatpickr) end._flatpickr.clear();
                        setTimeout(() => { this.isClearing = false; }, 100);
                    }
                  }">
                
                <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3">
                    <!-- Presets de Tiempo (Segmented Control) -->
                    <div class="flex bg-theme/5 p-1 rounded-xl border border-theme shrink-0">
                        <label class="relative flex items-center px-4 h-9 cursor-pointer rounded-lg transition-all duration-300"
                            :class="preset === 'current' ? 'bg-tecsisa-yellow text-black' : 'text-theme-muted hover:text-theme'">
                            <input type="radio" name="preset_week" value="current" x-model="preset" class="absolute opacity-0" @change="clearDates(); preset = 'current';">
                            <span class="text-[10px] font-black uppercase tracking-wider">Semana Corriente</span>
                        </label>
                        <label class="relative flex items-center px-4 h-9 cursor-pointer rounded-lg transition-all duration-300"
                            :class="preset === 'last' ? 'bg-tecsisa-yellow text-black' : 'text-theme-muted hover:text-theme'">
                            <input type="radio" name="preset_week" value="last" x-model="preset" class="absolute opacity-0" @change="clearDates(); preset = 'last';">
                            <span class="text-[10px] font-black uppercase tracking-wider">Semana Anterior</span>
                        </label>
                    </div>

                    <!-- Divisor sutil -->
                    <div class="hidden lg:block w-px h-6 bg-theme opacity-20 mx-1"></div>

                    <!-- Rango Manual y Parámetros en Grid -->
                    <div class="flex-1 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
                        <!-- Fecha Inicio -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-tecsisa-yellow/40 group-focus-within:text-tecsisa-yellow transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="text" name="start_date" id="start_date" value="{{ date('d/m/Y', strtotime($startDate)) }}" 
                                class="flatpickr-date w-full bg-theme/5 border border-theme rounded-xl text-[10px] h-11 pl-9 pr-3 font-bold text-theme placeholder-theme-muted/50 focus:bg-theme-card focus:border-tecsisa-yellow focus:ring-0 transition-all" 
                                placeholder="Fecha Inicio" @change="if(!isClearing) { preset = ''; }">
                        </div>

                        <!-- Fecha Fin -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-tecsisa-yellow/40 group-focus-within:text-tecsisa-yellow transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="text" name="end_date" id="end_date" value="{{ date('d/m/Y', strtotime($endDate)) }}" 
                                class="flatpickr-date w-full bg-theme/5 border border-theme rounded-xl text-[10px] h-11 pl-9 pr-3 font-bold text-theme placeholder-theme-muted/50 focus:bg-theme-card focus:border-tecsisa-yellow focus:ring-0 transition-all" 
                                placeholder="Fecha Fin" @change="if(!isClearing) { preset = ''; }">
                        </div>

                        <!-- Sistema -->
                        <select name="system_id" class="bg-theme/5 border border-theme rounded-xl text-[10px] font-bold h-11 px-3 text-theme focus:bg-theme-card focus:border-tecsisa-yellow focus:ring-0 transition-all appearance-none cursor-pointer">
                            <option value="">Todos los Sistemas</option>
                            @foreach($systems as $s)
                                <option value="{{ $s->id }}" {{ request('system_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>

                        <!-- Estado -->
                        <select name="status" class="bg-theme/5 border border-theme rounded-xl text-[10px] font-bold h-11 px-3 text-theme focus:bg-theme-card focus:border-tecsisa-yellow focus:ring-0 transition-all appearance-none cursor-pointer">
                            <option value="">Todos los Estados</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Activa</option>
                            <option value="in_review" {{ request('status') == 'in_review' ? 'selected' : '' }}>Aprobación</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Finalizado</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verificado</option>
                        </select>

                        <!-- Edificio -->
                        <input type="text" name="building" value="{{ request('building') }}" placeholder="Edificio..." 
                            class="bg-theme/5 border border-theme rounded-xl text-[10px] font-bold h-11 px-4 text-theme focus:bg-theme-card focus:border-tecsisa-yellow focus:ring-0 transition-all">

                        <!-- Técnico -->
                        <select name="technician_id" class="bg-theme/5 border border-theme rounded-xl text-[10px] font-bold h-11 px-3 text-theme focus:bg-theme-card focus:border-tecsisa-yellow focus:ring-0 transition-all appearance-none cursor-pointer">
                            <option value="">Todos los Técnicos</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ request('technician_id') == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botón Filtrar -->
                    <button type="submit" class="h-11 bg-tecsisa-yellow text-black font-black uppercase text-[10px] tracking-[0.15em] px-8 rounded-xl hover:bg-yellow-400 transition transform active:scale-95 shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <span>Buscar</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Vista Previa de Tabla -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
            <div class="px-8 py-6 border-b border-theme bg-theme/5 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-emerald-500/20 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-theme uppercase tracking-[0.3em]">Vista Previa de Reporte</h3>
                        <p class="text-[9px] text-theme-muted font-bold mt-0.5 uppercase tracking-widest">{{ $tasks->total() }} registros encontrados</p>
                    </div>
                </div>
                
                <!-- Acciones de Reporte (PDF/Excel) -->
                <div class="flex items-center justify-center gap-3 w-full md:w-auto">
                    <form action="{{ route('reports.weekly.generate') }}" method="POST" target="_blank" class="flex-1 sm:flex-none">
                        @csrf
                        <input type="hidden" name="system_id" value="{{ request('system_id') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="hidden" name="building" value="{{ request('building') }}">
                        <input type="hidden" name="technician_id" value="{{ request('technician_id') }}">
                        <input type="hidden" name="start_date" value="{{ date('d/m/Y', strtotime($startDate)) }}">
                        <input type="hidden" name="end_date" value="{{ date('d/m/Y', strtotime($endDate)) }}">
                        
                        <button type="submit" class="w-full h-11 px-5 bg-red-600 hover:bg-red-500 text-white font-black rounded-xl text-[9px] uppercase tracking-widest transition flex items-center justify-center gap-2 shadow-lg shadow-red-600/20 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <span>Exportar PDF</span>
                        </button>
                    </form>

                    <form action="{{ route('reports.export.internal') }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf
                        <input type="hidden" name="system_id" value="{{ request('system_id') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="hidden" name="building" value="{{ request('building') }}">
                        <input type="hidden" name="technician_id" value="{{ request('technician_id') }}">
                        <input type="hidden" name="start_date" value="{{ date('d/m/Y', strtotime($startDate)) }}">
                        <input type="hidden" name="end_date" value="{{ date('d/m/Y', strtotime($endDate)) }}">
                        
                        <button type="submit" class="w-full h-11 px-5 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-xl text-[9px] uppercase tracking-widest transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/20 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>Exportar xlsx</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-theme/5 border-b border-theme">
                            <th class="px-6 py-4 text-[9px] font-black text-theme-muted uppercase tracking-widest">Fecha</th>
                            <th class="px-6 py-4 text-[9px] font-black text-theme-muted uppercase tracking-widest">Ubicación</th>
                            <th class="px-6 py-4 text-[9px] font-black text-theme-muted uppercase tracking-widest">Sistema</th>
                            <th class="px-6 py-4 text-[9px] font-black text-theme-muted uppercase tracking-widest">TAG Equipo</th>
                            <th class="px-6 py-4 text-[9px] font-black text-theme-muted uppercase tracking-widest text-center">Materiales</th>
                            <th class="px-6 py-4 text-[9px] font-black text-theme-muted uppercase tracking-widest text-center">Responsable</th>
                            <th class="px-6 py-4 text-[9px] font-black text-theme-muted uppercase tracking-widest text-center">Estado</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-theme/5 transition-colors group">
                                <td class="px-6 py-4 text-[11px] font-bold text-theme">
                                    {{ $task->completed_at ? $task->completed_at->format('d/m/Y') : ($task->created_at ? $task->created_at->format('d/m/Y') : '-') }}
                                </td>
                                <td class="px-6 py-4 text-[10px] font-bold text-theme-muted">
                                    {{ $task->form_data['building'] ?? ($task->equipment->location->name ?? 'GENERAL') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-[8px] font-black uppercase text-white bg-blue-500/80 shadow-sm">
                                        {{ $task->system->name ?? ($task->equipment->system->name ?? 'GENERAL') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[10px] font-mono font-bold text-tecsisa-yellow">
                                    {{ $task->equipment->internal_id }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @if($task->has_new_cable) <span class="w-1.5 h-1.5 rounded-full bg-emerald-500" title="Cable"></span> @endif
                                        @if($task->has_new_jack) <span class="w-1.5 h-1.5 rounded-full bg-blue-500" title="Jack"></span> @endif
                                        @if($task->has_new_faceplate) <span class="w-1.5 h-1.5 rounded-full bg-yellow-500" title="Faceplate"></span> @endif
                                        @if(!$task->has_new_cable && !$task->has_new_jack && !$task->has_new_faceplate) <span class="text-[8px] text-theme-muted opacity-30 font-bold uppercase">N/A</span> @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-[10px] font-bold text-theme-muted">
                                    {{ explode(' ', $task->assignee->name)[0] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($task->status === 'completed')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black bg-blue-500/20 text-blue-400 uppercase tracking-tighter border border-blue-500/30">Finalizado</span>
                                    @elseif($task->status === 'verified')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black bg-emerald-500/20 text-emerald-400 uppercase tracking-tighter border border-emerald-500/30">Aprobado</span>
                                    @elseif($task->status === 'in_progress')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black bg-yellow-500/20 text-yellow-400 uppercase tracking-tighter border border-yellow-500/30">Activa</span>
                                    @elseif($task->status === 'in_review')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black bg-tecsisa-yellow/20 text-tecsisa-yellow uppercase tracking-tighter border border-tecsisa-yellow/30">Aprobación</span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black bg-theme/10 text-theme-muted uppercase tracking-tighter border border-theme">Borrador</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('tasks.edit', $task) }}" target="_blank" class="text-theme-muted hover:text-tecsisa-yellow transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-20 text-center text-theme-muted font-bold uppercase tracking-widest text-[10px]">
                                    <p>No se hallaron tareas con los filtros seleccionados.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($tasks->hasPages())
            <div class="px-8 py-4 bg-theme/5 border-t border-theme">
                {{ $tasks->links() }}
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('.flatpickr-date', {
                dateFormat: 'd/m/Y',
            });
        });
    </script>
    @endpush
</x-app-layout>
