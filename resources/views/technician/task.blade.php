<x-technician-layout>
    <!-- Header Especial para la Tarea -->
    <div class="fixed top-0 inset-x-0 z-40 bg-theme-header backdrop-blur-xl border-b border-theme pt-safe transition-colors duration-500">
        <div class="px-4 py-3 flex items-center justify-between">
            <a href="{{ route('tasks.index') }}" class="w-10 h-10 rounded-full bg-theme-card border border-theme flex items-center justify-center text-gray-400 transition shadow-lg" :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xs font-black uppercase tracking-widest transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Detalle: TK-{{ str_pad($task->id, 4, '0', STR_PAD_LEFT) }}</h1>
            <div class="w-10 h-10"></div> <!-- Placeholder for symmetry -->
        </div>
    </div>

    <!-- Espaciador Top Bar -->
    <div class="pt-20"></div>

    <div class="px-5 py-2" x-data="{ confirming: false }">
        
        <!-- Tarjeta Equipo -->
        <div class="bg-theme-card rounded-3xl border border-theme p-5 mb-6 relative overflow-hidden shadow-[0_10px_40px_rgba(0,0,0,0.5)] transition-colors duration-500">
            <!-- Deco -->
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-tecsisa-yellow/5 rounded-full blur-2xl"></div>

            <div class="flex justify-between items-start mb-4">
                <span class="bg-[#1a202c] border border-white/5 px-2.5 py-1 rounded text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    {{ $task->equipment->system->name ?? 'Activo General' }}
                </span>
                
                <span class="px-2 py-1 rounded text-[10px] font-black uppercase {{ $task->status === 'draft' ? 'bg-gray-800 text-gray-400' : ($task->status === 'in_progress' ? 'bg-tecsisa-yellow/20 text-tecsisa-yellow' : 'bg-green-500/20 text-green-400') }}">
                    {{ str_replace('_', ' ', $task->status) }}
                </span>
            </div>

            <h2 class="text-xl font-bold leading-tight mb-1 transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $task->equipment->name ?? 'Equipo Desconocido' }}</h2>
            <p class="text-sm font-mono text-tecsisa-yellow mb-6">ID: {{ $task->equipment->internal_id ?? 'N/A' }}</p>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-black/30 rounded-xl p-3 border border-white/5 transition-opacity duration-500" :class="theme === 'light' ? 'bg-slate-100 border-slate-200' : ''">
                    <span class="block text-[9px] uppercase font-bold tracking-widest text-gray-600 mb-1">Ubicación Fija</span>
                    <span class="text-xs font-bold truncate block flex items-center gap-1.5 transition-colors duration-500" :class="theme === 'light' ? 'text-slate-700' : 'text-gray-300'"><svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>{{ $task->equipment->location->name ?? 'No' }}</span>
                </div>
                <div class="bg-black/30 rounded-xl p-3 border border-white/5 transition-opacity duration-500" :class="theme === 'light' ? 'bg-slate-100 border-slate-200' : ''">
                    <span class="block text-[9px] uppercase font-bold tracking-widest text-gray-600 mb-1">Prioridad Interv.</span>
                    <span class="text-xs font-bold text-red-400 uppercase truncate block">
                        {{ $task->priority }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Instrucciones (Job Description) -->
        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Descripción de la Tarea</h3>
        <div class="bg-theme-card rounded-2xl p-5 border border-theme mb-8 transition-colors duration-500">
            <h4 class="text-lg font-bold mb-2 transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $task->title }}</h4>
            <p class="text-sm leading-relaxed transition-colors duration-500" :class="theme === 'light' ? 'text-slate-600' : 'text-gray-400'">{{ $task->description ?? 'Sin instrucciones adicionales' }}</p>
        </div>

        <form method="POST" action="{{ route('technician.task.update', $task->id) }}">
            @csrf
            @method('PUT')
            
            <!-- Checklists o Data entry Dinámico -->
            @if($task->status !== 'completed')
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Reporte Técnico (Formulario)</h3>
                <div class="bg-theme-card rounded-2xl p-5 border border-theme mb-8 space-y-4 transition-colors duration-500">
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Estado del Equipo post-revisión</label>
                        <select name="form_data[equipment_condition]" class="w-full border-theme rounded-xl focus:ring-tecsisa-yellow focus:border-tecsisa-yellow text-sm font-bold p-3 transition-colors duration-500" :class="theme === 'light' ? 'bg-slate-50 text-slate-800 border-slate-200' : 'bg-black text-white border-white/10'">
                            <option value="operative_optimal">Operativo - Óptimas Condiciones</option>
                            <option value="operative_with_notes">Operativo - Con Observaciones</option>
                            <option value="needs_replacement">Requiere Reemplazo</option>
                            <option value="out_of_service">Fuera de Servicio (Dañado)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Comentarios / Evidencia</label>
                        <textarea name="form_data[technician_notes]" rows="3" placeholder="Describe brevemente las acciones tomadas o refacciones utilizadas..." class="w-full border-theme rounded-xl focus:ring-tecsisa-yellow focus:border-tecsisa-yellow text-sm p-3 transition-colors duration-500" :class="theme === 'light' ? 'bg-slate-50 text-slate-800 border-slate-200' : 'bg-black text-white border-white/10'"></textarea>
                    </div>

                    <!-- Si necesitamos fotos podríamos añadir un campo de tipo file nativo de forms -->
                </div>
            @else
                 <div class="bg-green-500/10 border border-green-500/20 text-green-400 font-bold p-4 rounded-xl text-sm flex items-center justify-center gap-2 mb-8 uppercase tracking-widest text-center">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    Tarea Finalizada
                </div>
            @endif

            <!-- Botones de Acción (Sticking to bottom) -->
            @if($task->status !== 'completed')
                <div class="mt-4 pb-12 flex flex-col gap-3">
                    
                    <input type="hidden" name="status" x-bind:value="confirming ? 'completed' : 'in_progress'" id="statusInput">

                    
                    <button type="submit" @click="if(!confirming) { confirming = true; document.getElementById('statusInput').value='completed'; }" class="w-full bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black py-4 rounded-2xl text-sm uppercase tracking-widest shadow-[0_10px_30px_rgba(255,209,0,0.3)] transition transform active:scale-95 flex items-center justify-center gap-3">
                        <span x-text="confirming ? 'Firmar y Cerrar Tarea' : 'Completar Tarea Preventiva'"></span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </button>

                    <button type="button" x-show="confirming" @click="confirming = false" class="w-full bg-transparent border-2 border-theme font-bold py-3.5 rounded-2xl text-xs uppercase tracking-widest transition flex justify-center items-center gap-2" :class="theme === 'light' ? 'text-slate-800 hover:bg-slate-100' : 'text-white hover:bg-white/5'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Cancelar Cierre
                    </button>
                    
                    @if($task->status == 'draft' || $task->status == 'pending')
                    <button type="button" onclick="document.getElementById('statusInput').value='in_progress'; this.form.submit();" x-show="!confirming" class="w-full bg-transparent border-2 border-theme font-bold py-3.5 rounded-2xl text-xs uppercase tracking-widest transition flex justify-center items-center gap-2" :class="theme === 'light' ? 'text-slate-800 hover:bg-slate-100' : 'text-white hover:bg-white/5'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Marcar En Progreso (Iniciar Trabajo)
                    </button>
                    @endif
                </div>
            @endif
        </form>
    </div>
</x-technician-layout>
