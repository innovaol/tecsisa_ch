<x-technician-layout :hideNav="true" hideHeader="true">
    <div class="fixed top-0 inset-x-0 z-[60] bg-[#0a0d14]/95 backdrop-blur-3xl border-b border-white/5 pt-safe">
        <div class="px-4 py-4 flex items-center justify-between max-w-4xl mx-auto">
            <a href="{{ route('technician.scanner.result', $task->equipment_id) }}" class="md:hidden w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white transition shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <a href="{{ route('tasks.index') }}" class="hidden md:flex w-10 h-10 rounded-full bg-white/5 border border-white/10 items-center justify-center text-gray-400 hover:text-white transition shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xs md:text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-tecsisa-yellow animate-pulse"></span> Formulario Técnico
            </h1>
            <div class="w-10 h-10"></div>
        </div>
    </div>

    <div class="pt-20 pb-32 md:pb-10 px-5 max-w-4xl mx-auto relative z-10">
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
                
                if(actionType === 'save_draft' && !confirm('¿Deseas guardar los cambios actuales como borrador?')) {
                    return;
                }

                if(actionType === 'submit') {
                    if(!this.confirmFinal) {
                        alert('Debes marcar la casilla de confirmación de reporte profesional.');
                        return;
                    }
                    if(!confirm('¿Estás seguro de FINALIZAR este reporte? Una vez enviado no podrá ser editado.')) {
                        return;
                    }
                }

                this.isSubmitting = true;
                this.$refs.actionField.value = actionType;
                this.$refs.form.submit();
            }
        }" x-ref="form">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" value="save_draft" x-ref="actionField">

            <!-- 📸 SECCIÓN UNIVERSAL: EVIDENCIA VISUAL -->
            <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Registro Fotográfico (Antes y Después)
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-2 gap-3 md:gap-6 mb-8">
                <div class="bg-[#12161f] border border-white/5 p-4 md:p-6 rounded-3xl md:rounded-2xl flex flex-col items-center md:items-start text-center md:text-left">
                    <span class="text-[9px] md:text-xs uppercase font-black text-gray-500 mb-3 md:mb-4">Situación Inicial</span>
                    <div class="relative w-full aspect-square md:aspect-auto md:h-16 bg-black/60 rounded-2xl md:rounded-xl overflow-hidden flex flex-col md:flex-row items-center justify-center md:justify-start md:px-5 border border-dashed border-white/10 group active:scale-95 md:active:scale-100 md:hover:border-tecsisa-yellow/50 transition cursor-pointer">
                        @if(isset($task->form_data['photos']['before']))
                            <img src="{{ asset('storage/' . $task->form_data['photos']['before']) }}" class="absolute md:relative inset-0 w-full h-full md:w-10 md:h-10 object-cover md:rounded-md">
                            <span class="hidden md:block text-[10px] text-green-400 font-bold ml-4 uppercase tracking-widest">Cargada ✓</span>
                        @else
                            <div class="flex flex-col md:flex-row items-center gap-1 md:gap-3 pointer-events-none">
                                <svg class="w-6 h-6 md:w-5 md:h-5 text-gray-600 group-hover:text-tecsisa-yellow transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round"></path></svg>
                                <span class="text-[8px] md:text-[10px] font-black text-gray-700 md:text-gray-500 uppercase md:group-hover:text-tecsisa-yellow transition-colors tracking-widest">Explorar Archivos</span>
                            </div>
                        @endif
                        <input type="file" name="photos[before]" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                    </div>
                </div>
                <div class="bg-[#12161f] border border-white/5 p-4 md:p-6 rounded-3xl md:rounded-2xl flex flex-col items-center md:items-start text-center md:text-left">
                    <span class="text-[9px] md:text-xs uppercase font-black text-gray-500 mb-3 md:mb-4">Trabajo Finalizado</span>
                    <div class="relative w-full aspect-square md:aspect-auto md:h-16 bg-black/60 rounded-2xl md:rounded-xl overflow-hidden flex flex-col md:flex-row items-center justify-center md:justify-start md:px-5 border border-dashed border-white/10 group active:scale-95 md:active:scale-100 md:hover:border-tecsisa-yellow/50 transition cursor-pointer">
                        @if(isset($task->form_data['photos']['after']))
                            <img src="{{ asset('storage/' . $task->form_data['photos']['after']) }}" class="absolute md:relative inset-0 w-full h-full md:w-10 md:h-10 object-cover md:rounded-md">
                            <span class="hidden md:block text-[10px] text-green-400 font-bold ml-4 uppercase tracking-widest">Cargada ✓</span>
                        @else
                            <div class="flex flex-col md:flex-row items-center gap-1 md:gap-3 pointer-events-none">
                                <svg class="w-6 h-6 md:w-5 md:h-5 text-gray-600 group-hover:text-tecsisa-yellow transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round"></path></svg>
                                <span class="text-[8px] md:text-[10px] font-black text-gray-700 md:text-gray-500 uppercase md:group-hover:text-tecsisa-yellow transition-colors tracking-widest">Explorar Archivos</span>
                            </div>
                        @endif
                        <input type="file" name="photos[after]" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                    </div>
                </div>
            </div>

            <!-- 📊 SECCIÓN ESPECÍFICA: VOZ Y DATOS (FLUKE) -->
            @if($task->equipment->system_id == 2)
            <h3 class="text-[10px] font-black text-blue-500 uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Certificación Fluke Networks
            </h3>
            <div class="space-y-4 mb-8">
                <div class="bg-gradient-to-br from-[#12161f] to-[#0a0d14] border border-white/10 p-5 rounded-3xl shadow-xl">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 bg-blue-500/10 rounded-2xl border border-blue-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[9px] md:text-xs font-black text-blue-400 uppercase tracking-widest mb-1 leading-none text-center md:text-left">Captura de Pantalla Certificador</p>
                            <div class="relative w-full h-12 md:h-14 bg-black/50 border border-white/5 rounded-xl overflow-hidden flex items-center justify-center md:justify-start md:px-5 group active:scale-[0.98] transition cursor-pointer md:hover:border-tecsisa-yellow/50">
                                @if(isset($task->form_data['photos']['fluke_screen']))
                                    <span class="text-[10px] md:text-xs text-green-400 font-bold uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        Imagen Cargada
                                    </span>
                                @else
                                    <div class="flex items-center gap-2 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 group-hover:text-tecsisa-yellow transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest group-hover:text-tecsisa-yellow transition-colors">Adjuntar de Galería/Archivo</span>
                                    </div>
                                @endif
                                <input type="file" name="photos[fluke_screen]" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-black/40 p-3 rounded-2xl border border-white/5">
                            <label class="block text-[8px] font-black text-gray-500 uppercase mb-1">Margen (dB)</label>
                            <input type="text" name="form_data[fluke_margin]" value="{{ $task->form_data['fluke_margin'] ?? '' }}" placeholder="Ej: 6.4" class="w-full bg-transparent text-white font-mono font-bold text-center outline-none border-none p-0">
                        </div>
                        <div class="bg-black/40 p-3 rounded-2xl border border-white/5">
                            <label class="block text-[8px] font-black text-gray-500 uppercase mb-1">Longitud (m)</label>
                            <input type="text" name="form_data[fluke_length]" value="{{ $task->form_data['fluke_length'] ?? '' }}" placeholder="Ej: 42.1" class="w-full bg-transparent text-white font-mono font-bold text-center outline-none border-none p-0">
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 🔌 SECCIÓN ESPECÍFICA: DISPOSITIVOS ACTIVOS (CCTV, INCENDIOS, ETC) -->
            @if(in_array($task->equipment->system_id, [1, 3, 4]))
            <h3 class="text-[10px] font-black text-cyan-500 uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Diagnóstico de Dispositivo Activo
            </h3>
            <div class="space-y-4 mb-8">
                <div class="bg-[#12161f] border border-white/5 p-4 rounded-3xl">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-black text-gray-500 uppercase mb-2">Puerto / Canal</label>
                            <input type="text" name="form_data[active_port]" value="{{ $task->form_data['active_port'] ?? '' }}" placeholder="Ej: P-12" class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-sm px-4 py-3 focus:ring-1 focus:ring-cyan-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-gray-500 uppercase mb-2">Estado LEDs</label>
                            <select name="form_data[led_status]" class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-[10px] px-2 py-3 focus:ring-1 focus:ring-cyan-500 outline-none uppercase font-bold">
                                <option value="Normal" {{ ($task->form_data['led_status'] ?? '') == 'Normal' ? 'selected' : '' }}>Verde / Normal</option>
                                <option value="Alerta" {{ ($task->form_data['led_status'] ?? '') == 'Alerta' ? 'selected' : '' }}>Naranja / Alerta</option>
                                <option value="Error" {{ ($task->form_data['led_status'] ?? '') == 'Error' ? 'selected' : '' }}>Rojo / Fallo</option>
                                <option value="Apagado" {{ ($task->form_data['led_status'] ?? '') == 'Apagado' ? 'selected' : '' }}>Sin Actividad</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 🔧 SECCIÓN POR TIPO DE TAREA -->
            @if($task->task_type === 'maintenance')
                <h3 class="text-[10px] font-black text-tecsisa-yellow uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Checklist de Mantenimiento Prev.
                </h3>
                <div class="bg-[#12161f] border border-white/5 rounded-3xl p-5 mb-8 space-y-4 shadow-xl">
                    <label class="flex items-center justify-between p-3 bg-black/30 rounded-2xl border border-white/5 group active:bg-tecsisa-yellow/5">
                        <span class="text-xs font-bold text-gray-400 group-hover:text-white transition">Limpieza Física y Soplado</span>
                        <input type="checkbox" name="form_data[maint_clean]" value="1" {{ ($task->form_data['maint_clean'] ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded border-white/10 bg-black text-tecsisa-yellow focus:ring-tecsisa-yellow">
                    </label>
                    <label class="flex items-center justify-between p-3 bg-black/30 rounded-2xl border border-white/5 group active:bg-tecsisa-yellow/5">
                        <span class="text-xs font-bold text-gray-400 group-hover:text-white transition">Ajuste de Conectores / Peinado</span>
                        <input type="checkbox" name="form_data[maint_cables]" value="1" {{ ($task->form_data['maint_cables'] ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded border-white/10 bg-black text-tecsisa-yellow focus:ring-tecsisa-yellow">
                    </label>
                    <label class="flex items-center justify-between p-3 bg-black/30 rounded-2xl border border-white/5 group active:bg-tecsisa-yellow/5">
                        <span class="text-xs font-bold text-gray-400 group-hover:text-white transition">Verificación de Etiquetado</span>
                        <input type="checkbox" name="form_data[maint_tags]" value="1" {{ ($task->form_data['maint_tags'] ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded border-white/10 bg-black text-tecsisa-yellow focus:ring-tecsisa-yellow">
                    </label>
                </div>

            @elseif($task->task_type === 'replacement')
                <h3 class="text-[10px] font-black text-red-500 uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    Control de Sustitución
                </h3>
                <div class="bg-red-500/5 border border-red-500/10 rounded-3xl p-5 mb-8 space-y-4">
                    <div class="bg-black/40 p-3 rounded-2xl border border-white/5">
                        <label class="block text-[8px] font-black text-red-400 uppercase mb-1 tracking-tighter">S/N Equipo Entrante</label>
                        <input type="text" name="form_data[new_serial]" value="{{ $task->form_data['new_serial'] ?? '' }}" placeholder="Escanea serial nuevo..." class="w-full bg-transparent text-white font-mono font-bold text-sm outline-none border-none p-0">
                    </div>
                </div>

            @elseif($task->task_type === 'installation')
                <h3 class="text-[10px] font-black text-cyan-500 uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Aceptación de Instalación
                </h3>
                <div class="bg-cyan-500/5 border border-cyan-500/10 rounded-3xl p-5 mb-8 space-y-4">
                    <div class="bg-black/40 p-4 rounded-2xl border border-white/5">
                        <label class="block text-[9px] font-black text-cyan-400 uppercase mb-2">Prueba de Continuidad / Link</label>
                        <select name="form_data[install_test]" class="w-full bg-transparent text-white text-xs font-bold outline-none border-none p-0 uppercase">
                            <option value="PASS" {{ ($task->form_data['install_test'] ?? '') == 'PASS' ? 'selected' : '' }}>Prueba Exitosa (PASS) ✓</option>
                            <option value="FAIL" {{ ($task->form_data['install_test'] ?? '') == 'FAIL' ? 'selected' : '' }}>Prueba Fallida (FAIL) ✗</option>
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
            <div class="fixed bottom-0 inset-x-0 bg-[#0a0d14]/98 backdrop-blur-3xl border-t border-white/10 pb-safe z-[70] md:relative md:bg-transparent md:border-transparent md:p-0 md:mt-8">
                <div class="flex gap-4 p-5 max-w-lg mx-auto md:max-w-none md:p-0 md:justify-end">
                    <button type="button" @click="doSubmit('save_draft')" class="flex-1 md:flex-none md:w-auto bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold py-5 md:py-3 md:px-8 rounded-2xl text-[10px] md:text-xs uppercase tracking-[0.2em] transition" :class="{'opacity-50 cursor-not-allowed': isSubmitting}">
                        <span x-show="!isSubmitting">Borrador</span>
                        <span x-show="isSubmitting">...</span>
                    </button>
                    
                    <button type="button" @click="doSubmit('submit')" class="flex-[2] md:flex-none md:w-auto bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black py-5 md:py-3 md:px-8 rounded-2xl text-[10px] md:text-xs uppercase tracking-[0.2em] shadow-[0_15px_40px_rgba(255,209,0,0.4)] transition active:scale-95 flex items-center justify-center gap-2" :class="{'opacity-50 cursor-not-allowed': isSubmitting}">
                        <span x-show="!isSubmitting">Finalizar Reporte</span>
                        <span x-show="isSubmitting">Enviando Tarea...</span>
                        <svg x-show="!isSubmitting" class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </div>
            
            <!-- Safe Spacer for fixed bottom buttons -->
            <div class="h-32 md:hidden"></div>
        </form>
    </div>
</x-technician-layout>
