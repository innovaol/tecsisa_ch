<x-technician-layout :hideNav="true">
    <div class="fixed top-0 inset-x-0 z-[60] bg-[#0a0d14]/95 backdrop-blur-3xl border-b border-white/5 pt-safe">
        <div class="px-4 py-4 flex items-center justify-between">
            <a href="{{ route('technician.scanner.result', $task->equipment_id) }}" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white transition shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-tecsisa-yellow animate-pulse"></span> Formulario Técnico
            </h1>
            <div class="w-10 h-10"></div>
        </div>
    </div>

    <div class="pt-24 pb-40 px-5 relative z-10">
        <!-- Resumen del Equipo -->
        <div class="bg-[#12161f] rounded-3xl border border-white/10 p-5 mb-6 flex items-start gap-4">
            <div class="w-12 h-12 bg-black rounded-full border border-white/5 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-tecsisa-yellow font-mono text-[10px] uppercase font-bold tracking-widest mb-1">{{ $task->equipment->internal_id }}</p>
                <h2 class="text-sm font-bold text-white leading-tight mb-1">{{ $task->equipment->name }}</h2>
                <p class="text-xs text-gray-500">{{ $task->title }}</p>
            </div>
        </div>

        <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data" x-data="{
            isSubmitting: false,
            confirmFinal: false,
            doSubmit(actionType) {
                if(this.isSubmitting) return;
                if(actionType === 'submit' && !this.confirmFinal) {
                    alert('Debes marcar la casilla de confirmación de reporte profesional.');
                    return;
                }
                this.isSubmitting = true;
                $refs.actionField.value = actionType;
                $refs.form.submit();
            }
        }" x-ref="form">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" value="save_draft" x-ref="actionField">

            @if($task->task_type === 'maintenance')
                <h3 class="text-xs font-black text-tecsisa-yellow uppercase tracking-widest mb-3 px-1">Inspección {{ $task->equipment->system->name ?? 'General' }}</h3>
                <div class="space-y-4 mb-8">
                    <!-- Photo Header -->
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl flex flex-col items-center text-center">
                            <span class="text-[9px] uppercase font-bold text-gray-500 mb-3">Evidencia: Antes</span>
                            <div class="relative w-full aspect-square bg-black/60 rounded-xl overflow-hidden flex items-center justify-center border border-dashed border-white/20 group">
                                @if(isset($task->form_data['photos']['before']))
                                    <img src="{{ asset('storage/' . $task->form_data['photos']['before']) }}" class="absolute inset-0 w-full h-full object-cover">
                                @endif
                                <input type="file" name="photos[before]" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </div>
                        </div>
                        <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl flex flex-col items-center text-center">
                            <span class="text-[9px] uppercase font-bold text-gray-500 mb-3">Evidencia: Después</span>
                            <div class="relative w-full aspect-square bg-black/60 rounded-xl overflow-hidden flex items-center justify-center border border-dashed border-white/20 group">
                                @if(isset($task->form_data['photos']['after']))
                                    <img src="{{ asset('storage/' . $task->form_data['photos']['after']) }}" class="absolute inset-0 w-full h-full object-cover">
                                @endif
                                <input type="file" name="photos[after]" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </div>
                        </div>
                    </div>

                    @foreach($formSchema as $index => $field)
                        @php
                            $fieldName = 'form_data[' . ($field['name'] ?? 'field_' . $index) . ']';
                            $fieldValue = $task->form_data[$field['name'] ?? 'field_' . $index] ?? '';
                            $inputType = $field['type'] ?? 'text';
                            $label = $field['label'] ?? 'Campo';
                        @endphp
                        
                        <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl">
                            <label class="block text-gray-400 text-[10px] uppercase font-black tracking-widest mb-2">{{ $label }}</label>
                            
                            @if($inputType === 'text' || $inputType === 'number')
                                <input type="{{ $inputType }}" name="{{ $fieldName }}" value="{{ $fieldValue }}" 
                                       class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:ring-1 focus:ring-tecsisa-yellow transition placeholder-gray-700 outline-none">
                            @elseif($inputType === 'select' || $inputType === 'boolean')
                                <select name="{{ $fieldName }}" class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:ring-1 focus:ring-tecsisa-yellow transition outline-none">
                                    <option value="">-- SELECCIONAR --</option>
                                    @if($inputType === 'boolean')
                                        <option value="OK" {{ $fieldValue == 'OK' ? 'selected' : '' }}>ESTADO ÓPTIMO (O.K.)</option>
                                        <option value="FAIL" {{ $fieldValue == 'FAIL' ? 'selected' : '' }}>REQUIERE ATENCIÓN (FALLA)</option>
                                    @else
                                        @foreach(is_array($field['options']) ? $field['options'] : explode(',', $field['options']) as $opt)
                                            <option value="{{ trim($opt) }}" {{ $fieldValue == trim($opt) ? 'selected' : '' }}>{{ trim($opt) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            @endif
                        </div>
                    @endforeach
                </div>
            @elseif($task->task_type === 'replacement')
                <h3 class="text-xs font-black text-red-500 uppercase tracking-widest mb-3 px-1">Gestión de Sustitución</h3>
                <div class="space-y-4 mb-8">
                    <!-- Unit Comparison Photos -->
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl text-center">
                            <span class="text-[9px] uppercase font-bold text-gray-500 mb-2 block">Unidad Retirada</span>
                            <div class="relative w-full aspect-square bg-black/60 rounded-xl overflow-hidden flex items-center justify-center border border-dashed border-red-500/30 group">
                                @if(isset($task->form_data['photos']['old_unit']))
                                    <img src="{{ asset('storage/' . $task->form_data['photos']['old_unit']) }}" class="absolute inset-0 w-full h-full object-cover">
                                @endif
                                <input type="file" name="photos[old_unit]" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                                <svg class="w-6 h-6 text-red-900 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </div>
                        </div>
                        <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl text-center">
                            <span class="text-[9px] uppercase font-bold text-gray-500 mb-2 block">Unidad Nueva</span>
                            <div class="relative w-full aspect-square bg-black/60 rounded-xl overflow-hidden flex items-center justify-center border border-dashed border-green-500/30 group">
                                @if(isset($task->form_data['photos']['new_unit']))
                                    <img src="{{ asset('storage/' . $task->form_data['photos']['new_unit']) }}" class="absolute inset-0 w-full h-full object-cover">
                                @endif
                                <input type="file" name="photos[new_unit]" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                                <svg class="w-6 h-6 text-green-900 group-hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-black/30 p-4 rounded-2xl border border-white/5">
                        <label class="block text-gray-500 text-[9px] uppercase font-black mb-1">Activo Saliente</label>
                        <span class="text-white font-mono font-bold text-sm">{{ $task->equipment->internal_id }} / S/N: {{ $task->equipment->serial_number }}</span>
                    </div>

                    <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl">
                        <label class="block text-gray-400 text-[10px] uppercase font-black mb-2">Nuevo Número de Serie</label>
                        <input type="text" name="form_data[new_serial_number]" value="{{ $task->form_data['new_serial_number'] ?? '' }}" class="w-full bg-black/40 border border-white/10 rounded-xl text-tecsisa-yellow font-mono text-sm px-4 py-3 focus:ring-1 focus:ring-red-500 outline-none" placeholder="Escanee S/N nuevo...">
                    </div>

                    <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl">
                        <label class="block text-gray-400 text-[10px] uppercase font-black mb-2">MAC Address del Nuevo Equipo</label>
                        <input type="text" name="form_data[new_mac_address]" value="{{ $task->form_data['new_mac_address'] ?? '' }}" class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:ring-1 focus:ring-red-500 outline-none" placeholder="00:00:00:00:00:00">
                    </div>

                    <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl">
                        <label class="block text-gray-400 text-[10px] uppercase font-black mb-2">Estado del Equipo Retirado</label>
                        <select name="form_data[old_device_status]" class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:ring-1 focus:ring-red-500 outline-none">
                            <option value="Defectuoso" {{ ($task->form_data['old_device_status'] ?? '') == 'Defectuoso' ? 'selected' : '' }}>PARA REPARACIÓN / FALLA</option>
                            <option value="Desecho" {{ ($task->form_data['old_device_status'] ?? '') == 'Desecho' ? 'selected' : '' }}>OBSOLESCENCIA / DESECHO</option>
                            <option value="Backup" {{ ($task->form_data['old_device_status'] ?? '') == 'Backup' ? 'selected' : '' }}>OPERATIVO / RECIRCULACIÓN</option>
                        </select>
                    </div>
                </div>

            @elseif($task->task_type === 'installation')
                <h3 class="text-xs font-black text-cyan-500 uppercase tracking-widest mb-3 px-1">Protocolo de Instalación</h3>
                <div class="space-y-4 mb-8">
                    <!-- Installation Evidence -->
                    <div class="bg-[#12161f] border border-white/5 p-6 rounded-3xl flex flex-col items-center">
                        <span class="text-xs uppercase font-black text-gray-500 mb-4 tracking-tighter">Fotografía Final de Instalación</span>
                        <div class="relative w-full max-w-[240px] aspect-video bg-black/60 rounded-2xl overflow-hidden flex items-center justify-center border-2 border-dashed border-cyan-500/20 group">
                            @if(isset($task->form_data['photos']['installed']))
                                <img src="{{ asset('storage/' . $task->form_data['photos']['installed']) }}" class="absolute inset-0 w-full h-full object-cover">
                            @endif
                            <input type="file" name="photos[installed]" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                            <svg class="w-10 h-10 text-cyan-900 group-hover:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </div>
                    </div>

                    <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl">
                        <label class="block text-gray-400 text-[10px] uppercase font-black mb-2">Confirmación de Montaje</label>
                        <select name="form_data[mounting_status]" class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:ring-1 focus:ring-cyan-500 outline-none">
                            <option value="Rack" {{ ($task->form_data['mounting_status'] ?? '') == 'Rack' ? 'selected' : '' }}>INSTALADO EN RACK / RIEL</option>
                            <option value="Muro" {{ ($task->form_data['mounting_status'] ?? '') == 'Muro' ? 'selected' : '' }}>ADJUNTO A MURO / TECHO</option>
                            <option value="Superficie" {{ ($task->form_data['mounting_status'] ?? '') == 'Superficie' ? 'selected' : '' }}>SUPERFICIE / EXTERIOR</option>
                        </select>
                    </div>

                    <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl">
                        <label class="block text-gray-400 text-[10px] uppercase font-black mb-2">Etiquetado Tecsisa</label>
                        <select name="form_data[tagging]" class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:ring-1 focus:ring-cyan-500 outline-none">
                            <option value="SI" {{ ($task->form_data['tagging'] ?? '') == 'SI' ? 'selected' : '' }}>ETIQUETA FÍSICA APLICADA</option>
                            <option value="NO" {{ ($task->form_data['tagging'] ?? '') == 'NO' ? 'selected' : '' }}>PENDIENTE DE ETIQUETADO</option>
                        </select>
                    </div>

                    <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl">
                        <label class="block text-gray-400 text-[10px] uppercase font-black mb-2">Prueba de Comunicación (Ping/Test)</label>
                        <select name="form_data[connectivity_test]" class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:ring-1 focus:ring-cyan-500 outline-none">
                            <option value="PASS" {{ ($task->form_data['connectivity_test'] ?? '') == 'PASS' ? 'selected' : '' }}>PRUEBA EXITOSA (O.K.)</option>
                            <option value="FAIL" {{ ($task->form_data['connectivity_test'] ?? '') == 'FAIL' ? 'selected' : '' }}>FALLÓ CONECTIVIDAD</option>
                        </select>
                    </div>
                </div>
            @endif

            <!-- Área de Observaciones Generales (Aplicable a todas las tareas) -->
            <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1 text-center">Informe final del técnico</h3>
            <div class="bg-gradient-to-b from-[#12161f] to-[#0a0d14] border border-white/10 p-5 rounded-[2rem] mb-12 shadow-inner">
                <textarea name="description" rows="5"
                          class="w-full bg-transparent border-none text-gray-100 text-sm px-0 py-0 focus:ring-0 transition placeholder-gray-700 resize-none leading-relaxed"
                          placeholder="Escribe aquí el resumen ejecutivo técnico de la labor realizada...">{{ old('description', $task->description) }}</textarea>
                
                <div class="mt-4 pt-4 border-t border-white/5 flex items-center justify-between">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="w-6 h-6 rounded-lg border-2 border-white/10 flex items-center justify-center transition group-hover:border-tecsisa-yellow" :class="confirmFinal ? 'bg-tecsisa-yellow border-tecsisa-yellow' : 'bg-black/50'">
                            <svg x-show="confirmFinal" class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <input type="checkbox" x-model="confirmFinal" class="hidden">
                        <span class="text-[10px] font-black uppercase text-gray-500 tracking-tighter" :class="confirmFinal ? 'text-tecsisa-yellow' : ''">Certifico labor de alta calidad</span>
                    </label>
                </div>
            </div>

            <!-- Botones Flotantes de Acción Fijos Inferiores -->
            <div class="fixed bottom-0 inset-x-0 bg-[#0a0d14]/98 backdrop-blur-3xl border-t border-white/10 pb-safe z-[70]">
                <div class="flex gap-4 p-5 max-w-lg mx-auto">
                    <button type="button" @click="doSubmit('save_draft')" class="flex-1 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold py-5 rounded-2xl text-[10px] uppercase tracking-[0.2em] transition" :class="{'opacity-50 cursor-not-allowed': isSubmitting}">
                        <span x-show="!isSubmitting">Borrador</span>
                        <span x-show="isSubmitting">...</span>
                    </button>
                    
                    <button type="button" @click="doSubmit('submit')" class="flex-[2] bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black py-5 rounded-2xl text-[10px] uppercase tracking-[0.2em] shadow-[0_15px_40px_rgba(255,209,0,0.4)] transition active:scale-95 flex items-center justify-center gap-2" :class="{'opacity-50 cursor-not-allowed': isSubmitting}">
                        <span x-show="!isSubmitting">Finalizar Reporte</span>
                        <span x-show="isSubmitting">Enviando Tarea...</span>
                        <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </div>
            
            <!-- Safe Spacer for fixed bottom buttons -->
            <div class="h-32"></div>
        </form>
    </div>
</x-technician-layout>
