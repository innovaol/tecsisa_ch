<x-technician-layout>
    <div class="fixed top-0 inset-x-0 z-40 bg-[#0a0d14]/90 backdrop-blur-xl border-b border-white/5 pt-safe">
        <div class="px-4 py-3 flex items-center justify-between">
            <a href="{{ route('technician.scanner') }}" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-tecsisa-yellow animate-pulse"></span> Formulario Técnico
            </h1>
            <div class="w-10 h-10"></div>
        </div>
    </div>

    <div class="pt-20 pb-24 px-5">
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

        <form action="{{ route('tasks.update', $task) }}" method="POST" x-data="{
            isSubmitting: false,
            doSubmit(actionType) {
                if(this.isSubmitting) return;
                this.isSubmitting = true;
                $refs.actionField.value = actionType;
                $refs.form.submit();
            }
        }" x-ref="form">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" value="save_draft" x-ref="actionField">

            <!-- Dynamic Schema Render -->
            @if(count($formSchema) > 0)
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">Inspección {{ $task->equipment->system->name ?? 'General' }}</h3>
                <div class="space-y-4 mb-8">
                    @foreach($formSchema as $index => $field)
                        @php
                            $fieldName = 'form_data[' . ($field['name'] ?? 'field_' . $index) . ']';
                            $fieldValue = $task->form_data[$field['name'] ?? 'field_' . $index] ?? '';
                            $inputType = $field['type'] ?? 'text';
                            $label = $field['label'] ?? 'Campo Desconocido';
                        @endphp
                        
                        <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl">
                            <label class="block text-gray-300 text-xs font-bold mb-2">{{ $label }}</label>
                            
                            @if($inputType === 'text' || $inputType === 'number')
                                <input type="{{ $inputType }}" name="{{ $fieldName }}" value="{{ $fieldValue }}" 
                                       class="w-full bg-black/50 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:outline-none focus:border-tecsisa-yellow focus:ring-1 focus:ring-tecsisa-yellow transition placeholder-gray-600"
                                       placeholder="Ingresar {{ strtolower($label) }}...">
                                       
                            @elseif($inputType === 'textarea')
                                <textarea name="{{ $fieldName }}" rows="3"
                                          class="w-full bg-black/50 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:outline-none focus:border-tecsisa-yellow focus:ring-1 focus:ring-tecsisa-yellow transition placeholder-gray-600"
                                          placeholder="Detallar...">{{ $fieldValue }}</textarea>
                                          
                            @elseif($inputType === 'select' || $inputType === 'boolean')
                                <select name="{{ $fieldName }}" 
                                        class="w-full bg-black/50 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:outline-none focus:border-tecsisa-yellow focus:ring-1 focus:ring-tecsisa-yellow transition appearance-none relative">
                                    <option value="">Seleccione...</option>
                                    @if($inputType === 'boolean')
                                        <option value="true" {{ $fieldValue == 'true' ? 'selected' : '' }}>Sí / Correcto</option>
                                        <option value="false" {{ $fieldValue == 'false' ? 'selected' : '' }}>No / Falla</option>
                                    @else
                                        @foreach(explode(',', $field['options'] ?? '') as $opt)
                                            @php $optTrimmed = trim($opt); @endphp
                                            <option value="{{ $optTrimmed }}" {{ $fieldValue === $optTrimmed ? 'selected' : '' }}>{{ $optTrimmed }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Componente de advertencia de error/falta de plantilla -->
                <div class="bg-blue-500/10 border border-blue-500/20 rounded-2xl p-5 mb-8 flex gap-4 items-start">
                    <svg class="w-6 h-6 text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h4 class="text-sm font-bold text-white mb-1">Sin Guía Específica</h4>
                        <p class="text-xs text-blue-300">Este sistema no tiene un formulario técnico predefinido. Completa los detalles generales de tu intervención abajo y anota las piezas reemplazadas o acciones tomadas.</p>
                    </div>
                </div>
            @endif

            <!-- Área de Observaciones Generales (Aplicable a todas las tareas) -->
            <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">Notas de la Intervención</h3>
            <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl mb-8">
                <textarea name="description" rows="4"
                          class="w-full bg-black/50 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:outline-none focus:border-tecsisa-yellow focus:ring-1 focus:ring-tecsisa-yellow transition placeholder-gray-600"
                          placeholder="Describe con máximo detalle qué procedimientos técnicos u observaciones realizaste en sitio...">{{ old('description', $task->description) }}</textarea>
            </div>

            <!-- Botones Flotantes de Acción Fijos Inferiores -->
            <div class="fixed bottom-0 inset-x-0 bg-[#0a0d14]/90 backdrop-blur-3xl border-t border-white/5 pb-safe z-40 p-4">
                <div class="flex gap-3 max-w-lg mx-auto">
                    <button type="button" @click="doSubmit('save_draft')" class="flex-1 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold py-4 rounded-xl text-xs uppercase tracking-widest transition" :class="{'opacity-50 cursor-not-allowed': isSubmitting}">
                        <span x-show="!isSubmitting">Guardar Avance</span>
                        <span x-show="isSubmitting">Cargando...</span>
                    </button>
                    
                    <button type="button" @click="doSubmit('submit')" class="flex-[2] bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black py-4 rounded-xl text-xs uppercase tracking-widest shadow-[0_10px_30px_rgba(255,209,0,0.3)] transition border-2 border-transparent" :class="{'opacity-50 cursor-not-allowed': isSubmitting}">
                        <span x-show="!isSubmitting">Finalizar y Reportar</span>
                        <span x-show="isSubmitting">Enviando...</span>
                    </button>
                </div>
            </div>
            
            <!-- Safe Spacer for fixed bottom buttons -->
            <div class="h-10"></div>
        </form>
    </div>
</x-technician-layout>
