<x-technician-layout>
    <!-- Header Especial para la Tarea -->
    <div class="fixed top-0 inset-x-0 z-40 bg-theme-header backdrop-blur-xl border-b border-theme pt-safe transition-colors duration-500">
        <div class="px-4 py-3 flex items-center justify-between">
            <a href="{{ route('tasks.index') }}" class="w-11 h-11 flex items-center justify-center bg-theme/5 border border-theme text-theme-muted hover:text-tecsisa-yellow rounded-2xl transition shadow-md active:scale-90 group shrink-0">
                <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-xs font-black uppercase tracking-widest transition-colors duration-500 flex items-center gap-2" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                <span>{{ $task->task_type }}: TK-{{ str_pad($task->id, 4, '0', STR_PAD_LEFT) }}</span>
            </h1>
            <div class="w-10 h-10"></div>
        </div>
    </div>

    <!-- Espaciador Top Bar -->
    <div class="pt-20"></div>

    <div class="px-5 py-2" x-data="{ 
        confirming: false,
        taskType: '{{ $task->task_type }}',
        materials: {!! json_encode($task->form_data['materials'] ?? []) !!},
        addMaterial() {
            this.materials.push({ name: '', qty: 1 });
        },
        removeMaterial(index) {
            this.materials.splice(index, 1);
        }
    }">
        
        <!-- Tarjeta Equipo -->
        <div class="bg-theme-card rounded-3xl border border-theme p-5 mb-6 relative overflow-hidden shadow-xl transition-colors duration-500">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-tecsisa-yellow/5 rounded-full blur-2xl"></div>

            <div class="flex justify-between items-start mb-4">
                <span class="bg-[#1a202c] border border-white/5 px-2.5 py-1 rounded text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    {{ $task->equipment->system->name ?? 'Activo General' }}
                </span>
                
                <span class="px-2 py-1 rounded text-[10px] font-black uppercase {{ $task->status === 'draft' ? 'bg-gray-800 text-gray-400' : (in_array($task->status, ['pending', 'in_progress']) ? 'bg-tecsisa-yellow/20 text-tecsisa-yellow' : ($task->status === 'in_review' ? 'bg-blue-500/20 text-blue-400' : 'bg-green-500/20 text-green-400')) }}">
                    @if($task->status === 'draft') Borrador
                    @elseif(in_array($task->status, ['pending', 'in_progress'])) Activa
                    @elseif($task->status === 'in_review') Pendiente
                    @elseif($task->status === 'completed') Finalizada
                    @else {{ $task->status }} @endif
                </span>
            </div>

            <h2 class="text-xl font-bold leading-tight mb-1 transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $task->equipment->name ?? 'Equipo Desconocido' }}</h2>
            <p class="text-sm font-mono text-tecsisa-yellow mb-6">TAG: {{ $task->equipment->internal_id ?? 'N/A' }}</p>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-black/30 rounded-xl p-3 border border-white/5 transition-opacity duration-500" :class="theme === 'light' ? 'bg-slate-100 border-slate-200' : ''">
                    <span class="block text-[9px] uppercase font-bold tracking-widest text-gray-600 mb-1">Ubicación</span>
                    <span class="text-xs font-bold truncate block flex items-center gap-1.5 transition-colors duration-500" :class="theme === 'light' ? 'text-slate-700' : 'text-gray-300'">{{ $task->equipment->location->name ?? 'No' }}</span>
                </div>
                <div class="bg-black/30 rounded-xl p-3 border border-white/5 transition-opacity duration-500" :class="theme === 'light' ? 'bg-slate-100 border-slate-200' : ''">
                    <span class="block text-[9px] uppercase font-bold tracking-widest text-gray-600 mb-1">Prioridad</span>
                    <span class="text-xs font-bold text-red-400 uppercase truncate block">{{ $task->priority }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('technician.task.update', $task->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            @if($task->status !== 'completed')

                {{-- ✅ 1. EVALUACIÓN (Solo Mantenimiento) --}}
                @if(($task->task_type === 'Mantenimiento' || $task->task_type === 'maintenance') && count($checklist) > 0)
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Evaluación de Mantenimiento
                </h3>
                <div class="bg-theme-card rounded-2xl border border-theme mb-8 overflow-hidden transition-colors duration-500"
                     x-data="{ evaluations: {!! json_encode(collect($task->form_data['evaluation'] ?? [])->mapWithKeys(fn($e,$i) => [$i => $e['status'] ?? ''])) !!} }">
                    @foreach($checklist as $idx => $item)
                    <div class="flex flex-col gap-2 px-5 py-4 {{ $idx > 0 ? 'border-t border-theme' : '' }}">
                        <p class="text-[10px] font-bold text-theme-muted uppercase leading-tight">{{ $idx + 1 }}. {{ $item }}</p>
                        <input type="hidden" name="form_data[evaluation][{{ $idx }}][item]" value="{{ $item }}">
                        <div class="flex items-center gap-3">
                            <label class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl border cursor-pointer transition-all active:scale-95"
                                   :class="evaluations[{{ $idx }}] === 'SI' ? 'bg-emerald-500/20 border-emerald-500/40 text-emerald-400' : 'bg-theme/5 border-theme text-gray-500'">
                                <input type="radio" name="form_data[evaluation][{{ $idx }}][status]" value="SI" x-model="evaluations[{{ $idx }}]" class="hidden">
                                <span class="text-[10px] font-black uppercase tracking-widest text-center w-full">SI / OK</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl border cursor-pointer transition-all active:scale-95"
                                   :class="evaluations[{{ $idx }}] === 'NO' ? 'bg-red-500/20 border-red-500/40 text-red-400' : 'bg-theme/5 border-theme text-gray-500'">
                                <input type="radio" name="form_data[evaluation][{{ $idx }}][status]" value="NO" x-model="evaluations[{{ $idx }}]" class="hidden">
                                <span class="text-[10px] font-black uppercase tracking-widest text-center w-full">NO / FALLA</span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- ✅ 2. DATOS DE SUSTITUCIÓN (Solo Sustitución) --}}
                @if($task->task_type === 'Sustitución' || $task->task_type === 'replacement')
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Trazabilidad de Cambio</h3>
                <div class="bg-theme-card rounded-2xl p-5 border border-theme mb-8 space-y-4 transition-colors duration-500">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2">Serial del Equipo Retirado</label>
                        <input type="text" name="form_data[swap][serial_out]" value="{{ $task->equipment->serial_number }}" class="w-full bg-black/20 border-theme rounded-xl text-sm font-bold p-3 text-red-400" readonly>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Serial del Equipo Entrante</label>
                        <input type="text" name="form_data[swap][serial_in]" value="{{ $task->form_data['swap']['serial_in'] ?? '' }}" placeholder="Ingrese nuevo serial..." class="w-full bg-theme/5 border-theme rounded-xl text-sm font-bold p-3 text-theme focus:ring-tecsisa-yellow focus:border-tecsisa-yellow">
                    </div>
                </div>
                @endif

                {{-- ✅ 3. MATERIALES (Insumos) — Siempre visible --}}
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                    <span>Insumos / Conectores Utilizados</span>
                    <button type="button" @click="addMaterial()" class="text-tecsisa-yellow hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                </h3>
                <div class="bg-theme-card rounded-2xl p-5 border border-theme mb-8 space-y-3 transition-colors duration-500">
                    <template x-for="(material, index) in materials" :key="index">
                        <div class="flex gap-2 items-center">
                            <input type="text" :name="'form_data[materials][' + index + '][name]'" x-model="material.name" placeholder="Ej: Jack RJ45 Cat6" class="flex-1 bg-theme/5 border-theme rounded-xl text-xs p-3 text-theme min-w-0">
                            <input type="number" :name="'form_data[materials][' + index + '][qty]'" x-model="material.qty" class="w-16 bg-theme/5 border-theme rounded-xl text-xs p-3 text-center text-theme">
                            <button type="button" @click="removeMaterial(index)" class="p-2 text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </div>
                    </template>
                    <div x-show="materials.length === 0" class="text-center py-4 text-[10px] text-gray-500 uppercase font-black tracking-widest opacity-50">Pulse (+) para añadir insumos</div>
                </div>

                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Reporte de Estado Final</h3>
                <div class="bg-theme-card rounded-2xl p-5 border border-theme mb-8 space-y-4 transition-colors duration-500">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Estado del Equipo</label>
                        <select name="form_data[equipment_condition]" class="w-full border-theme rounded-xl focus:ring-tecsisa-yellow focus:border-tecsisa-yellow text-xs font-bold p-3 transition-colors duration-500" :class="theme === 'light' ? 'bg-slate-50 text-slate-800' : 'bg-black text-white'">
                            <option value="operative_optimal" {{ ($task->form_data['equipment_condition'] ?? '') == 'operative_optimal' ? 'selected' : '' }}>Operativo - Óptimas Condiciones</option>
                            <option value="operative_with_notes" {{ ($task->form_data['equipment_condition'] ?? '') == 'operative_with_notes' ? 'selected' : '' }}>Operativo - Con Observaciones</option>
                            <option value="needs_replacement" {{ ($task->form_data['equipment_condition'] ?? '') == 'needs_replacement' ? 'selected' : '' }}>Requiere Reemplazo</option>
                            <option value="out_of_service" {{ ($task->form_data['equipment_condition'] ?? '') == 'out_of_service' ? 'selected' : '' }}>Fuera de Servicio</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Observaciones Técnicas</label>
                        <textarea name="form_data[technician_notes]" rows="3" placeholder="Detalle acciones tomadas..." class="w-full border-theme rounded-xl focus:ring-tecsisa-yellow focus:border-tecsisa-yellow text-xs p-3 transition-colors duration-500" :class="theme === 'light' ? 'bg-slate-50 text-slate-800' : 'bg-black text-white'">{{ $task->form_data['technician_notes'] ?? '' }}</textarea>
                    </div>
                </div>

                {{-- ✅ 4. EVIDENCIA (Basado en Reportes: Fotos Inicial/Final) --}}
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Evidencia Fotográfica</h3>
                <div class="bg-theme-card rounded-2xl p-6 border border-theme mb-12 grid grid-cols-2 gap-4 transition-colors duration-500">
                    <div class="flex flex-col items-center gap-3">
                        <span class="text-[9px] font-black text-gray-500 uppercase">Situación Inicial</span>
                        <div class="w-full aspect-video rounded-xl bg-black/20 border-2 border-dashed border-theme flex flex-col items-center justify-center text-gray-600 relative overflow-hidden group">
                           @if(isset($task->form_data['photos']['before'])) 
                               <img src="{{ asset('storage/' . $task->form_data['photos']['before']) }}" class="absolute inset-0 w-full h-full object-cover">
                           @endif
                           <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                           <input type="file" name="photo_before" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                    </div>
                    <div class="flex flex-col items-center gap-3">
                        <span class="text-[9px] font-black text-gray-500 uppercase">Trabajo Terminado</span>
                        <div class="w-full aspect-video rounded-xl bg-black/20 border-2 border-dashed border-theme flex flex-col items-center justify-center text-gray-600 relative overflow-hidden">
                           @if(isset($task->form_data['photos']['after'])) 
                               <img src="{{ asset('storage/' . $task->form_data['photos']['after']) }}" class="absolute inset-0 w-full h-full object-cover">
                           @endif
                           <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                           <input type="file" name="photo_after" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                    </div>
                </div>

            @else
                 {{-- VISTA LECTURA --}}
                 <div class="bg-green-500/10 border border-green-500/20 text-green-400 font-bold p-4 rounded-xl text-sm flex items-center justify-center gap-2 mb-8 uppercase tracking-widest text-center">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    Tarea Finalizada
                </div>
                <!-- ... resumen lectura similar al anterior ... -->
            @endif

            <!-- Botones de Acción -->
            @if($task->status !== 'completed')
                <div class="fixed bottom-0 inset-x-0 p-5 bg-theme-header/80 backdrop-blur-md border-t border-theme z-50">
                    <input type="hidden" name="status" x-bind:value="confirming ? 'completed' : 'in_progress'" id="statusInput">
                    <div class="flex flex-col gap-3">
                        <button type="submit" @click="if(!confirming) { confirming = true; document.getElementById('statusInput').value='completed'; }" class="w-full bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black py-4 rounded-2xl text-[10px] uppercase tracking-[0.2em] shadow-lg transition active:scale-95">
                             <span x-text="confirming ? 'Firmar y Cerrar Reporte' : 'Guardar y Finalizar'"></span>
                        </button>
                        <button type="button" x-show="confirming" @click="confirming = false" class="w-full text-gray-500 font-bold py-2 text-[10px] uppercase tracking-widest">Regresar</button>
                        
                        @if($task->status == 'draft' || $task->status == 'pending')
                        <button type="button" onclick="document.getElementById('statusInput').value='in_progress'; this.form.submit();" x-show="!confirming" class="w-full border border-theme text-theme font-bold py-3.5 rounded-2xl text-[10px] uppercase tracking-widest">
                            Guardar Borrador
                        </button>
                        @endif
                    </div>
                </div>
                <div class="h-40"></div>
            @endif
        </form>
    </div>
</x-technician-layout>
