<x-technician-layout>
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

            @if($task->task_type === 'maintenance')
                <!-- Dynamic Schema Render for Maintenance -->
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
                                            @php
                                                $optsRaw = $field['options'] ?? '';
                                                $optsArray = is_array($optsRaw) ? $optsRaw : explode(',', (string)$optsRaw);
                                            @endphp
                                            @foreach($optsArray as $opt)
                                                @php $optTrimmed = trim((string)$opt); @endphp
                                                @if($optTrimmed !== '')
                                                    <option value="{{ $optTrimmed }}" {{ $fieldValue === $optTrimmed ? 'selected' : '' }}>{{ $optTrimmed }}</option>
                                                @endif
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
            @elseif($task->task_type === 'replacement')
                <!-- Formulario Específico para Reemplazo Físico -->
                <h3 class="text-xs font-black text-red-500 uppercase tracking-widest mb-3 px-1">Datos de Reemplazo (Hardware)</h3>
                <div class="space-y-4 mb-8">
                    <div class="bg-red-500/5 border border-red-500/20 p-4 rounded-2xl">
                        <label class="block text-gray-300 text-xs font-bold mb-2">ID/Placa del Equipo Retirado</label>
                        <input type="text" name="form_data[old_internal_id]" value="{{ $task->form_data['old_internal_id'] ?? $task->equipment->internal_id }}" readonly class="w-full bg-black/50 border border-white/10 rounded-xl text-gray-400 text-sm px-4 py-3 cursor-not-allowed">
                    </div>
                    <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl">
                        <label class="block text-gray-300 text-xs font-bold mb-2">N/S Fabricante del Equipo NUEVO</label>
                        <input type="text" name="form_data[new_serial_number]" value="{{ $task->form_data['new_serial_number'] ?? '' }}" class="w-full bg-black/50 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:outline-none focus:border-red-500" placeholder="Escanee o ingrese serial nuevo...">
                    </div>
                    <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl">
                        <label class="block text-gray-300 text-xs font-bold mb-2">MAC Address (Si aplica)</label>
                        <input type="text" name="form_data[new_mac_address]" value="{{ $task->form_data['new_mac_address'] ?? '' }}" class="w-full bg-black/50 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:outline-none focus:border-red-500" placeholder="Ej: 00:1A:2B:3C:4D:5E">
                    </div>
                    <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl">
                        <label class="block text-gray-300 text-xs font-bold mb-2">Motivo de Reemplazo</label>
                        <select name="form_data[replacement_reason]" class="w-full bg-black/50 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:outline-none focus:border-red-500">
                            <option value="">Seleccione motivo...</option>
                            <option value="Daño irreparable" {{ ($task->form_data['replacement_reason'] ?? '') == 'Daño irreparable' ? 'selected' : '' }}>Daño irreparable / Quemado</option>
                            <option value="Obsolescencia" {{ ($task->form_data['replacement_reason'] ?? '') == 'Obsolescencia' ? 'selected' : '' }}>Obsolescencia Programada</option>
                            <option value="Upgrade" {{ ($task->form_data['replacement_reason'] ?? '') == 'Upgrade' ? 'selected' : '' }}>Mejora / Upgrade</option>
                        </select>
                    </div>
                </div>
            @elseif($task->task_type === 'installation')
                <!-- Formulario Específico para Instalación Inicial -->
                <h3 class="text-xs font-black text-cyan-500 uppercase tracking-widest mb-3 px-1">Checklist de Instalación</h3>
                <div class="space-y-4 mb-8">
                    <div class="bg-cyan-500/5 border border-cyan-500/20 p-4 rounded-2xl">
                        <label class="block text-gray-300 text-xs font-bold mb-2">N/S Fabricante Instalado</label>
                        <input type="text" name="form_data[installed_serial_number]" value="{{ $task->form_data['installed_serial_number'] ?? $task->equipment->serial_number }}" class="w-full bg-black/50 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:border-cyan-500">
                    </div>
                    <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl">
                        <label class="block text-gray-300 text-xs font-bold mb-2">MAC Address / IP Asignada</label>
                        <input type="text" name="form_data[installed_mac_ip]" value="{{ $task->form_data['installed_mac_ip'] ?? '' }}" class="w-full bg-black/50 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:border-cyan-500" placeholder="Ej: IP 192.168.1.5 o MAC">
                    </div>
                    <div class="bg-[#12161f] border border-white/5 p-4 rounded-2xl">
                        <label class="block text-gray-300 text-xs font-bold mb-2">Pruebas Iniciales (Encendido y Link)</label>
                        <select name="form_data[initial_tests_passed]" class="w-full bg-black/50 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:border-cyan-500">
                            <option value="">Seleccione status...</option>
                            <option value="Exitosas" {{ ($task->form_data['initial_tests_passed'] ?? '') == 'Exitosas' ? 'selected' : '' }}>Pruebas Exitosas. Equipo O.K.</option>
                            <option value="Fallidas" {{ ($task->form_data['initial_tests_passed'] ?? '') == 'Fallidas' ? 'selected' : '' }}>Fallo en arranque inicial</option>
                            <option value="Pendientes" {{ ($task->form_data['initial_tests_passed'] ?? '') == 'Pendientes' ? 'selected' : '' }}>Faltan configuraciones de red</option>
                        </select>
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
            <div class="fixed bottom-0 inset-x-0 bg-[#0a0d14]/95 backdrop-blur-3xl border-t border-white/10 pb-safe z-50 p-5">
                <div class="flex gap-4 max-w-lg mx-auto">
                    <button type="button" @click="doSubmit('save_draft')" class="flex-1 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold py-5 rounded-2xl text-[10px] uppercase tracking-widest transition" :class="{'opacity-50 cursor-not-allowed': isSubmitting}">
                        <span x-show="!isSubmitting">Guardar Avance</span>
                        <span x-show="isSubmitting">Procesando...</span>
                    </button>
                    
                    <button type="button" @click="doSubmit('submit')" class="flex-[2] bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black py-5 rounded-2xl text-[10px] uppercase tracking-widest shadow-[0_15px_35px_rgba(255,209,0,0.4)] transition active:scale-95" :class="{'opacity-50 cursor-not-allowed': isSubmitting}">
                        <span x-show="!isSubmitting">Finalizar Reporte</span>
                        <span x-show="isSubmitting">Enviando...</span>
                    </button>
                </div>
            </div>
            
            <!-- Safe Spacer for fixed bottom buttons -->
            <div class="h-20"></div>
        </form>
    </div>
</x-technician-layout>
