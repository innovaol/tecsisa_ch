<x-technician-layout>
    <!-- Header Especial para la Tarea -->
    <div class="fixed top-0 inset-x-0 z-40 bg-theme-header backdrop-blur-xl border-b border-theme pt-safe transition-colors duration-500">
        <div class="px-4 py-3 flex items-center justify-between">
            <a href="{{ route('tasks.index') }}" class="w-11 h-11 flex items-center justify-center bg-theme/5 border border-theme text-theme-muted hover:text-tecsisa-yellow rounded-2xl transition shadow-md active:scale-90 group shrink-0">
                <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-xs font-black uppercase tracking-widest transition-colors duration-500 flex items-center gap-2" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                <span>Detalle: TK-{{ str_pad($task->id, 4, '0', STR_PAD_LEFT) }}</span>
                <div class="group relative inline-block">
                    <svg class="w-5 h-5 text-theme-muted cursor-help p-0.5 hover:text-tecsisa-yellow transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0114 0z"></path></svg>
                    <div class="absolute top-full right-0 mt-3 w-64 p-4 bg-black/95 text-[11px] text-white rounded-2xl opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-[100] border border-theme shadow-2xl normal-case font-bold backdrop-blur-md origin-top-right">
                        <div class="absolute -top-1.5 right-4 w-3 h-3 bg-black/95 border-t border-l border-theme rotate-45"></div>
                        Formulario de reporte técnico para trazabilidad de mantenimiento.
                    </div>
                </div>
            </h1>
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
                
                <span class="px-2 py-1 rounded text-[10px] font-black uppercase {{ $task->status === 'draft' ? 'bg-gray-800 text-gray-400' : (in_array($task->status, ['pending', 'in_progress']) ? 'bg-tecsisa-yellow/20 text-tecsisa-yellow' : ($task->status === 'in_review' ? 'bg-blue-500/20 text-blue-400' : 'bg-green-500/20 text-green-400')) }}">
                    @if($task->status === 'draft') Borrador
                    @elseif(in_array($task->status, ['pending', 'in_progress'])) Activa
                    @elseif($task->status === 'in_review') Aprobación
                    @elseif($task->status === 'completed') Finalizada
                    @else {{ $task->status }} @endif
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

                {{-- ✅ EVALUACIÓN TÉCNICA (CHECKLIST) — solo si el sistema tiene ítems definidos --}}
                @if(count($checklist) > 0)
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Evaluación Técnica de Actividades
                </h3>
                <div class="bg-theme-card rounded-2xl border border-theme mb-8 overflow-hidden shadow-lg transition-colors duration-500"
                     x-data="{
                        evaluations: {!! json_encode(collect($task->form_data['evaluation'] ?? [])->mapWithKeys(fn($e,$i) => [$i => $e['status'] ?? ''])) !!}
                     }">
                    @foreach($checklist as $idx => $item)
                    <div class="flex flex-col gap-2 px-5 py-4 {{ $idx > 0 ? 'border-t border-theme' : '' }}">
                        {{-- Texto de la actividad --}}
                        <p class="text-[10px] font-bold text-theme-muted uppercase leading-tight">{{ $idx + 1 }}. {{ $item }}</p>

                        <input type="hidden" name="form_data[evaluation][{{ $idx }}][item]" value="{{ $item }}">

                        {{-- Botones SI / NO --}}
                        <div class="flex items-center gap-3">
                            <label class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl border cursor-pointer transition-all active:scale-95"
                                   :class="evaluations[{{ $idx }}] === 'SI'
                                       ? 'bg-emerald-500/20 border-emerald-500/40 text-emerald-400'
                                       : 'bg-theme/5 border-theme text-gray-500'">
                                <input type="radio" name="form_data[evaluation][{{ $idx }}][status]" value="SI"
                                       x-model="evaluations[{{ $idx }}]" class="hidden">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-[10px] font-black uppercase tracking-widest">SI</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl border cursor-pointer transition-all active:scale-95"
                                   :class="evaluations[{{ $idx }}] === 'NO'
                                       ? 'bg-red-500/20 border-red-500/40 text-red-400'
                                       : 'bg-theme/5 border-theme text-gray-500'">
                                <input type="radio" name="form_data[evaluation][{{ $idx }}][status]" value="NO"
                                       x-model="evaluations[{{ $idx }}]" class="hidden">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span class="text-[10px] font-black uppercase tracking-widest">NO</span>
                            </label>
                        </div>

                        {{-- Campo de comentario (solo visible si marcó algo) --}}
                        <div x-show="evaluations[{{ $idx }}] !== undefined && evaluations[{{ $idx }}] !== ''" x-transition>
                            <input type="text"
                                   name="form_data[evaluation][{{ $idx }}][comment]"
                                   value="{{ $task->form_data['evaluation'][$idx]['comment'] ?? '' }}"
                                   placeholder="Comentario opcional..."
                                   class="w-full border-b border-theme bg-transparent text-xs text-theme-muted placeholder-gray-600 focus:text-theme focus:border-tecsisa-yellow outline-none py-1.5 transition">
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

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

                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Resumen de Resultados</h3>
                <div class="bg-theme-card rounded-2xl p-5 border border-theme mb-8 space-y-6 transition-colors duration-500">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-black/20 rounded-xl border border-white/5">
                            <span class="block text-[10px] text-gray-500 font-bold uppercase mb-1">Estado Final</span>
                            <span class="text-sm font-bold text-white uppercase">{{ str_replace('_', ' ', $task->form_data['equipment_condition'] ?? 'N/A') }}</span>
                        </div>
                        <div class="p-3 bg-black/20 rounded-xl border border-white/5">
                            <span class="block text-[10px] text-gray-500 font-bold uppercase mb-1">Fecha Cierre</span>
                            <span class="text-sm font-bold text-white">{{ $task->completed_at ? $task->completed_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </div>

                    @if(!empty($task->form_data['technician_notes']))
                    <div>
                        <span class="block text-[10px] text-gray-500 font-bold uppercase mb-2">Notas del Técnico</span>
                        <div class="text-sm text-gray-300 bg-black/20 p-4 rounded-xl border border-white/5 leading-relaxed">
                            {{ $task->form_data['technician_notes'] }}
                        </div>
                    </div>
                    @endif

                    @if(!empty($task->form_data['materials']))
                    <div>
                        <span class="block text-[10px] text-gray-500 font-bold uppercase mb-2">Materiales Utilizados</span>
                        <div class="space-y-2">
                            @foreach($task->form_data['materials'] as $material)
                                <div class="flex justify-between items-center bg-black/20 p-2.5 rounded-lg border border-white/5">
                                    <span class="text-xs text-gray-300 font-bold">{{ $material['name'] }}</span>
                                    <span class="bg-tecsisa-yellow/20 text-tecsisa-yellow px-2 py-0.5 rounded text-[10px] font-black">{{ $material['qty'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(!empty($task->form_data['photos']))
                    <div>
                        <span class="block text-[10px] text-gray-500 font-bold uppercase mb-3">Evidencia Fotográfica</span>
                        <div class="grid grid-cols-2 gap-3">
                            @if(isset($task->form_data['photos']['before']))
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $task->form_data['photos']['before']) }}" class="w-full h-32 object-cover rounded-xl border border-white/10">
                                    <span class="absolute bottom-2 left-2 bg-black/60 backdrop-blur px-2 py-0.5 rounded text-[9px] font-bold text-white uppercase">Antes</span>
                                </div>
                            @endif
                            @if(isset($task->form_data['photos']['after']))
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $task->form_data['photos']['after']) }}" class="w-full h-32 object-cover rounded-xl border border-white/10">
                                    <span class="absolute bottom-2 left-2 bg-black/60 backdrop-blur px-2 py-0.5 rounded text-[9px] font-bold text-white uppercase">Después</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(!empty($task->form_data['findings']))
                    <div>
                        <span class="block text-[10px] text-gray-500 font-bold uppercase mb-3">Hallazgos Registrados</span>
                        <div class="space-y-4">
                            @foreach($task->form_data['findings'] as $finding)
                                <div class="bg-black/20 rounded-xl overflow-hidden border border-white/5">
                                    @if(isset($finding['photo']))
                                        <img src="{{ asset('storage/' . $finding['photo']) }}" class="w-full h-40 object-cover">
                                    @endif
                                    <div class="p-3">
                                        <p class="text-xs text-gray-400 italic">{{ $finding['caption'] ?? 'Sin descripción' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            @endif

            <!-- Botones de Acción (Sticking to bottom) -->
            @if($task->status !== 'completed')
                <div class="mt-4 pb-12 flex flex-col gap-3">
                    
                    <input type="hidden" name="status" x-bind:value="confirming ? 'completed' : 'in_progress'" id="statusInput">

                    
                    <button type="submit" @click="if(!confirming) { confirming = true; document.getElementById('statusInput').value='completed'; }" class="w-full bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black py-4 rounded-2xl text-[10px] uppercase tracking-widest shadow-[0_10px_30px_rgba(255,209,0,0.3)] transition transform active:scale-95 flex items-center justify-center gap-3">
                        <span x-text="confirming ? 'Firmar y Cerrar' : 'Guardar y Finalizar'"></span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </button>

                    <button type="button" x-show="confirming" @click="confirming = false" class="w-full bg-transparent border-2 border-theme font-bold py-3.5 rounded-2xl text-xs uppercase tracking-widest transition flex justify-center items-center gap-2" :class="theme === 'light' ? 'text-slate-800 hover:bg-slate-100' : 'text-white hover:bg-white/5'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Cancelar Cierre
                    </button>
                    
                    @if($task->status == 'draft' || $task->status == 'pending')
                    <button type="button" onclick="document.getElementById('statusInput').value='in_progress'; this.form.submit();" x-show="!confirming" class="w-full bg-transparent border-2 border-theme font-bold py-3.5 rounded-2xl text-xs uppercase tracking-widest transition flex justify-center items-center gap-2" :class="theme === 'light' ? 'text-slate-800' : 'hover:bg-slate-100' : 'text-white hover:bg-white/5'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="sm:hidden">Iniciar</span>
                        <span class="hidden sm:inline">Iniciar Trabajo</span>
                    </button>
                    @endif
                </div>
            @endif
        </form>
    </div>
</x-technician-layout>
