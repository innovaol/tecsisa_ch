<x-technician-layout :hideHeader="true" :hideNav="false">
    <!-- Contextual Header -->
    <div class="fixed top-0 inset-x-0 z-[60] bg-[#0a0d14]/90 backdrop-blur-xl border-b border-white/5 pt-safe">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('tasks.index') }}" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-tecsisa-yellow transition active:scale-90">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h1 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-tecsisa-yellow animate-pulse"></span> 
                        {{ $isReadOnly ? 'Vista de Reporte' : 'Edición de Tarea' }}
                    </h1>
                </div>
                <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest hidden sm:block">
                    ID: {{ $task->id }}
                </div>
            </div>
        </div>
    </div>

    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto relative z-10">
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

        <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data" 
              x-data="technicianForm({
                  status: '{{ $task->status }}',
                  findings: {{ json_encode($task->form_data['findings'] ?? []) }},
                  materials: {{ json_encode($task->form_data['materials'] ?? []) }},
                  previews: {
                      before: '{{ isset($task->form_data['photos']['before']) ? asset('storage/' . $task->form_data['photos']['before']) : '' }}',
                      after: '{{ isset($task->form_data['photos']['after']) ? asset('storage/' . $task->form_data['photos']['after']) : '' }}',
                      fluke_screen: '{{ isset($task->form_data['photos']['fluke_screen']) ? asset('storage/' . $task->form_data['photos']['fluke_screen']) : '' }}'
                  },
                  storagePath: '{{ asset('storage') }}/'
              })" 
              x-ref="form" id="tech-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" value="save_draft" x-ref="actionField">

            @php
                $isReadOnly = in_array($task->status, ['completed', 'verified']);
            @endphp

            <!-- 📸 SECCIÓN UNIVERSAL: EVIDENCIA VISUAL -->
            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Registro Fotográfico (Obligatorio)
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <!-- Foto Inicial -->
                <div class="bg-[#12161f] border border-white/5 p-6 rounded-[2.5rem] flex flex-col items-center text-center shadow-2xl relative overflow-hidden">
                    <span class="text-[9px] uppercase font-black text-gray-500 mb-4 tracking-[0.2em] flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Situación Inicial
                    </span>
                    
                    <div class="w-full relative">
                        <div class="relative w-full aspect-video bg-black/60 rounded-3xl overflow-hidden flex flex-col items-center justify-center border border-dashed border-white/10 shadow-inner mb-4 transition-all duration-300" :class="previews.before ? 'border-solid border-tecsisa-yellow/30' : 'border-dashed border-white/10'">
                            <template x-if="previews.before">
                                <img :src="previews.before" class="absolute inset-0 w-full h-full object-cover">
                            </template>
                            <template x-if="!previews.before">
                                <div class="flex flex-col items-center gap-3 opacity-20">
                                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="1.5" stroke-linecap="round"></path></svg>
                                </div>
                            </template>
                        </div>

                        <!-- Acciones Dinámicas -->
                        <div class="w-full">
                            @unless($isReadOnly)
                            <div x-show="!previews.before" class="flex flex-col gap-3">
                                <div class="flex md:hidden w-full gap-3">
                                    <label class="flex-1 flex flex-col items-center gap-2 bg-tecsisa-yellow p-4 rounded-2xl text-black active:scale-95 transition cursor-pointer">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                        <span class="text-[8px] font-black uppercase tracking-widest leading-none">Cámara</span>
                                        <input type="file" name="photos[before_capture]" capture="environment" class="hidden" accept="image/*" @change="previewPhoto($event, 'before')">
                                    </label>
                                    <label class="flex-1 flex flex-col items-center gap-2 bg-white/10 border border-white/10 p-4 rounded-2xl text-white active:scale-95 transition cursor-pointer">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-[8px] font-black uppercase tracking-widest leading-none">Galería</span>
                                        <input type="file" name="photos[before]" class="hidden" accept="image/*" @change="previewPhoto($event, 'before')">
                                    </label>
                                </div>
                                <label class="hidden md:flex w-full items-center justify-center gap-3 bg-white/5 border border-white/10 hover:border-tecsisa-yellow/50 p-4 rounded-2xl text-gray-400 hover:text-tecsisa-yellow transition cursor-pointer group">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="text-xs font-black uppercase tracking-widest leading-none">Seleccionar Archivo</span>
                                    <input type="file" name="photos[before]" class="hidden" accept="image/*" @change="previewPhoto($event, 'before')">
                                </label>
                            </div>
                                <div x-show="previews.before" class="flex gap-3">
                                    <button type="button" @click="removePhoto('before')" class="flex-1 h-12 bg-red-500/10 border border-red-500/20 rounded-xl text-red-500 text-[10px] font-black uppercase tracking-widest active:scale-95 transition flex items-center justify-center gap-2">Quitar</button>
                                    <button type="button" @click="removePhoto('before')" class="flex-1 h-12 bg-white/5 border border-white/10 rounded-xl text-white text-[10px] font-black uppercase tracking-widest active:scale-95 transition flex items-center justify-center gap-2">Repetir</button>
                                </div>
                            </div>
                            @endunless
                        </div>
                    </div>

                <!-- Foto Final -->
                <div class="bg-[#12161f] border border-white/5 p-6 rounded-[2.5rem] flex flex-col items-center text-center shadow-2xl relative overflow-hidden">
                    <span class="text-[9px] uppercase font-black text-gray-500 mb-4 tracking-[0.2em] flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Trabajo Finalizado
                    </span>
                    
                    <div class="w-full relative">
                        <div class="relative w-full aspect-video bg-black/60 rounded-3xl overflow-hidden flex flex-col items-center justify-center border border-dashed border-white/10 shadow-inner mb-4 transition-all duration-300" :class="previews.after ? 'border-solid border-tecsisa-yellow/30' : 'border-dashed border-white/10'">
                            <template x-if="previews.after">
                                <img :src="previews.after" class="absolute inset-0 w-full h-full object-cover">
                            </template>
                            <template x-if="!previews.after">
                                <div class="flex flex-col items-center gap-3 opacity-20">
                                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="1.5" stroke-linecap="round"></path></svg>
                                </div>
                            </template>
                        </div>

                        <!-- Acciones Dinámicas -->
                        <div class="w-full">
                            @unless($isReadOnly)
                            <div x-show="!previews.after" class="flex flex-col gap-3">
                                <div class="flex md:hidden w-full gap-3">
                                    <label class="flex-1 flex flex-col items-center gap-2 bg-tecsisa-yellow p-4 rounded-2xl text-black active:scale-95 transition cursor-pointer">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                        <span class="text-[8px] font-black uppercase tracking-widest leading-none">Cámara</span>
                                        <input type="file" name="photos[after_capture]" capture="environment" class="hidden" accept="image/*" @change="previewPhoto($event, 'after')">
                                    </label>
                                    <label class="flex-1 flex flex-col items-center gap-2 bg-white/10 border border-white/10 p-4 rounded-2xl text-white active:scale-95 transition cursor-pointer">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-[8px] font-black uppercase tracking-widest leading-none">Galería</span>
                                        <input type="file" name="photos[after]" class="hidden" accept="image/*" @change="previewPhoto($event, 'after')">
                                    </label>
                                </div>
                                <label class="hidden md:flex w-full items-center justify-center gap-3 bg-white/5 border border-white/10 hover:border-tecsisa-yellow/50 p-4 rounded-2xl text-gray-400 hover:text-tecsisa-yellow transition cursor-pointer group">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="text-xs font-black uppercase tracking-widest leading-none">Seleccionar Archivo</span>
                                    <input type="file" name="photos[after]" class="hidden" accept="image/*" @change="previewPhoto($event, 'after')">
                                </label>
                            </div>
                            <div x-show="previews.after" class="flex gap-3">
                                <button type="button" @click="removePhoto('after')" class="flex-1 h-12 bg-red-500/10 border border-red-500/20 rounded-xl text-red-500 text-[10px] font-black uppercase tracking-widest active:scale-95 transition flex items-center justify-center gap-2">Quitar</button>
                                <button type="button" @click="removePhoto('after')" class="flex-1 h-12 bg-white/5 border border-white/10 rounded-xl text-white text-[10px] font-black uppercase tracking-widest active:scale-95 transition flex items-center justify-center gap-2">Repetir</button>
                            </div>
                            @endunless
                        </div>
                    </div>
                </div>
            </div>

            <!-- 📸 SECCIÓN: GALERÍA DE HALLAZGOS (HALLAZGOS TÉCNICOS) -->
            <h3 class="text-[10px] font-black text-tecsisa-yellow uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Hallazgos Técnicos (Evidencia Detallada)
            </h3>
            <div class="space-y-4 mb-10">
                <template x-for="(f, index) in findings" :key="index">
                    <div class="bg-gradient-to-br from-[#12161f] to-[#0a0d14] border border-white/10 p-4 rounded-3xl relative shadow-2xl overflow-hidden group">
                        @unless($isReadOnly)
                        <button type="button" @click="removeFinding(index)" class="absolute top-4 right-4 text-red-400/50 hover:text-red-400 transition z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        @endunless
                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-56">
                                <div class="relative w-full aspect-square md:aspect-video bg-black/60 rounded-2xl border border-dashed border-white/10 flex flex-col items-center justify-center shadow-inner relative overflow-hidden transition-all duration-300" :class="previews.findings[index] ? 'border-solid border-tecsisa-yellow/30' : 'border-dashed border-white/10'">
                                    <template x-if="previews.findings[index]">
                                        <img :src="previews.findings[index]" class="absolute inset-0 w-full h-full object-cover">
                                    </template>
                                    
                                    <template x-if="!previews.findings[index]">
                                        <div class="flex flex-col items-center gap-2 opacity-10">
                                            <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    </template>
                                </div>

                                <!-- Acciones Dinámicas Findings -->
                                <div class="mt-3">
                                    @unless($isReadOnly)
                                    <!-- Mobile Controls -->
                                    <div x-show="!previews.findings[index]" class="flex md:hidden gap-3 w-full">
                                        <label class="flex-1 flex flex-col items-center gap-2 bg-tecsisa-yellow p-3 rounded-xl text-black active:scale-95 transition cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                            <span class="text-[7px] font-black uppercase tracking-widest leading-none">Cámara</span>
                                            <input type="file" :name="'finding_photos_capture['+index+']'" capture="environment" class="hidden" accept="image/*" @change="previewPhoto($event, 'findings', index)">
                                        </label>
                                        <label class="flex-1 flex flex-col items-center gap-2 bg-white/10 border border-white/10 p-3 rounded-xl text-white active:scale-95 transition cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-[7px] font-black uppercase tracking-widest leading-none">Galería</span>
                                            <input type="file" :name="'finding_photos['+index+']'" class="hidden" accept="image/*" @change="previewPhoto($event, 'findings', index)">
                                        </label>
                                    </div>
                                    
                                    <!-- PC Control -->
                                    <label x-show="!previews.findings[index]" class="hidden md:flex flex-col items-center gap-2 text-gray-500 hover:text-tecsisa-yellow transition cursor-pointer bg-white/5 border border-white/10 p-4 rounded-xl">
                                        <svg class="w-6 h-6 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <span class="text-[8px] font-black uppercase tracking-widest">Añadir Archivo</span>
                                        <input type="file" :name="'finding_photos['+index+']'" class="hidden" accept="image/*" @change="previewPhoto($event, 'findings', index)">
                                    </label>

                                    <!-- Action Buttons if Photo Exists -->
                                    <div x-show="previews.findings[index]" class="flex gap-2">
                                        <button type="button" @click="removePhoto('findings', index)" class="flex-1 h-10 bg-red-500/10 border border-red-500/20 rounded-lg text-red-500 text-[8px] font-black uppercase active:scale-95 transition">Quitar</button>
                                        <button type="button" @click="removePhoto('findings', index)" class="flex-1 h-10 bg-white/5 border border-white/10 rounded-lg text-white text-[8px] font-black uppercase active:scale-95 transition">Repetir</button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <label class="block text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1.5 px-1">Leyenda / Hallazgo observado</label>
                                <textarea :name="'finding_captions['+index+']'" x-model="f.caption" rows="2" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-black/40 border-none rounded-2xl text-[10px] font-bold text-gray-200 p-4 leading-relaxed focus:ring-1 focus:ring-tecsisa-yellow/30 placeholder:text-gray-700 transition disabled:opacity-70 disabled:cursor-not-allowed" placeholder="{{ $isReadOnly ? '' : 'Describa el detalle observado en sitio...' }}"></textarea>
                                <input type="hidden" :name="'finding_paths['+index+']'" :value="f.photo">
                            </div>
                        </div>
                    </div>
                </template>
                
                @unless($isReadOnly)
                <button type="button" @click="addFinding()" class="w-full h-16 bg-white/5 border border-dashed border-white/10 rounded-3xl flex items-center justify-center gap-3 text-gray-500 hover:text-tecsisa-yellow hover:border-tecsisa-yellow/30 active:scale-95 transition duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span class="text-[9px] font-black uppercase tracking-[0.2em]">Añadir Nuevo Hallazgo</span>
                </button>
                @endunless
            </div>

            <!-- 🛠️ SECCIÓN: INSUMOS Y MATERIALES UTILIZADOS -->
            <h3 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Materiales e Insumos Utilizados
            </h3>
            <div class="space-y-3 mb-12">
                <div class="bg-[#12161f] rounded-3xl border border-white/5 p-4">
                    <template x-for="(m, index) in materials" :key="index">
                        <div class="flex items-center gap-3 mb-3 animate-fadeIn">
                            <div class="flex-1">
                                <input type="text" name="material_names[]" x-model="m.name" placeholder="Ej: Metros de Cable CAT6" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-black/40 border border-white/5 rounded-xl text-[10px] text-white font-bold h-11 px-4 focus:ring-1 focus:ring-emerald-500/30 disabled:opacity-70">
                            </div>
                            <div class="w-24">
                                <input type="number" name="material_qtys[]" x-model="m.qty" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-black/40 border border-white/5 rounded-xl text-[10px] text-white font-bold h-11 px-2 text-center focus:ring-1 focus:ring-emerald-500/30 disabled:opacity-70">
                            </div>
                            <button type="button" @click="removeMaterial(index)" class="text-red-400 p-2 hover:bg-red-500/10 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>
                    
                    @unless($isReadOnly)
                    <button type="button" @click="addMaterial()" class="w-full h-11 bg-emerald-500/5 border border-dashed border-emerald-500/20 rounded-xl flex items-center justify-center gap-2 text-emerald-500/60 hover:text-emerald-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <span class="text-[9px] font-black uppercase tracking-widest">Añadir Insumo (Cable, RJ45, etc)</span>
                    </button>
                    @endunless
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
                    <div class="flex flex-col md:flex-row items-center gap-6 mb-8">
                        <div class="w-24 h-24 bg-blue-500/10 rounded-2xl border border-blue-500/20 flex items-center justify-center shrink-0 relative overflow-hidden shadow-inner transition-all duration-300" :class="previews.fluke_screen ? 'border-solid border-blue-400' : 'border-dashed border-blue-500/20'">
                            <template x-if="previews.fluke_screen">
                                <img :src="previews.fluke_screen" class="absolute inset-0 w-full h-full object-cover">
                            </template>
                            <template x-if="!previews.fluke_screen">
                                <svg class="w-10 h-10 text-blue-400 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </template>
                        </div>
                        
                        <div class="flex-1 w-full">
                            <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-3 leading-none text-center md:text-left">Captura de Pantalla Certificador</p>
                            
                            <!-- Acciones Dinámicas Fluke -->
                            <div class="w-full">
                                @unless($isReadOnly)
                                <!-- Estado: Sin Foto -->
                                <div x-show="!previews.fluke_screen" class="flex flex-col md:flex-row gap-3">
                                    <!-- Mobile -->
                                    <div class="flex md:hidden gap-3 w-full">
                                        <label class="flex-1 flex items-center justify-center gap-2 bg-tecsisa-yellow h-12 rounded-xl text-black active:scale-95 transition cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                            <span class="text-[8px] font-black uppercase tracking-widest leading-none">Cámara</span>
                                            <input type="file" name="photos[fluke_screen_capture]" capture="environment" class="hidden" accept="image/*" @change="previewPhoto($event, 'fluke_screen')">
                                        </label>
                                        <label class="flex-1 flex items-center justify-center gap-2 bg-white/10 border border-white/10 h-12 rounded-xl text-white active:scale-95 transition cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-[8px] font-black uppercase tracking-widest leading-none">Galería</span>
                                            <input type="file" name="photos[fluke_screen]" class="hidden" accept="image/*" @change="previewPhoto($event, 'fluke_screen')">
                                        </label>
                                    </div>
                                    <!-- PC -->
                                    <label class="hidden md:flex flex-1 items-center justify-center gap-3 bg-white/5 border border-white/10 hover:border-tecsisa-yellow/50 h-12 rounded-xl text-gray-400 hover:text-tecsisa-yellow transition cursor-pointer group px-4">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Seleccionar Archivo</span>
                                        <input type="file" name="photos[fluke_screen]" class="hidden" accept="image/*" @change="previewPhoto($event, 'fluke_screen')">
                                    </label>
                                </div>
                                
                                <!-- Estado: Con Foto -->
                                <div x-show="previews.fluke_screen" class="flex gap-3">
                                    <button type="button" @click="removePhoto('fluke_screen')" class="flex-1 h-12 bg-red-500/10 border border-red-500/20 rounded-xl text-red-500 text-[10px] font-black uppercase tracking-widest active:scale-95 transition flex items-center justify-center gap-2">Quitar</button>
                                    <button type="button" @click="removePhoto('fluke_screen')" class="flex-1 h-12 bg-white/5 border border-white/10 rounded-xl text-white text-[10px] font-black uppercase tracking-widest active:scale-95 transition flex items-center justify-center gap-2">Repetir</button>
                                </div>
                                @endunless
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-black/40 p-4 rounded-2xl border border-white/5">
                            <label class="block text-[8px] font-black text-gray-500 uppercase mb-1 tracking-widest">Margen (dB)</label>
                            <input type="text" name="form_data[fluke_margin]" value="{{ $task->form_data['fluke_margin'] ?? '' }}" placeholder="Ej: 6.4" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-transparent text-white font-mono font-bold text-center outline-none border-none p-0 disabled:opacity-70">
                        </div>
                        <div class="bg-black/40 p-4 rounded-2xl border border-white/5">
                            <label class="block text-[8px] font-black text-gray-500 uppercase mb-1 tracking-widest">Longitud (m)</label>
                            <input type="text" name="form_data[fluke_length]" value="{{ $task->form_data['fluke_length'] ?? '' }}" placeholder="Ej: 42.1" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-transparent text-white font-mono font-bold text-center outline-none border-none p-0 disabled:opacity-70">
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
                        <input type="checkbox" name="form_data[maint_clean]" value="1" {{ ($task->form_data['maint_clean'] ?? false) ? 'checked' : '' }} {{ $isReadOnly ? 'disabled' : '' }} class="w-5 h-5 rounded border-white/10 bg-black text-tecsisa-yellow focus:ring-tecsisa-yellow disabled:opacity-50">
                    </label>
                    <label class="flex items-center justify-between p-3 bg-black/30 rounded-2xl border border-white/5 group active:bg-tecsisa-yellow/5">
                        <span class="text-xs font-bold text-gray-400 group-hover:text-white transition">Ajuste de Conectores / Peinado</span>
                        <input type="checkbox" name="form_data[maint_cables]" value="1" {{ ($task->form_data['maint_cables'] ?? false) ? 'checked' : '' }} {{ $isReadOnly ? 'disabled' : '' }} class="w-5 h-5 rounded border-white/10 bg-black text-tecsisa-yellow focus:ring-tecsisa-yellow disabled:opacity-50">
                    </label>
                    <label class="flex items-center justify-between p-3 bg-black/30 rounded-2xl border border-white/5 group active:bg-tecsisa-yellow/5">
                        <span class="text-xs font-bold text-gray-400 group-hover:text-white transition">Verificación de Etiquetado</span>
                        <input type="checkbox" name="form_data[maint_tags]" value="1" {{ ($task->form_data['maint_tags'] ?? false) ? 'checked' : '' }} {{ $isReadOnly ? 'disabled' : '' }} class="w-5 h-5 rounded border-white/10 bg-black text-tecsisa-yellow focus:ring-tecsisa-yellow disabled:opacity-50">
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
                        <input type="text" name="form_data[new_serial]" value="{{ $task->form_data['new_serial'] ?? '' }}" placeholder="Escanea serial nuevo..." {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-transparent text-white font-mono font-bold text-sm outline-none border-none p-0 disabled:opacity-70">
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
                        <select name="form_data[install_test]" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-transparent text-white text-xs font-bold outline-none border-none p-0 uppercase disabled:opacity-70">
                            <option value="PASS" {{ ($task->form_data['install_test'] ?? '') == 'PASS' ? 'selected' : '' }}>Prueba Exitosa (PASS) ✓</option>
                            <option value="FAIL" {{ ($task->form_data['install_test'] ?? '') == 'FAIL' ? 'selected' : '' }}>Prueba Fallida (FAIL) ✗</option>
                        </select>
                    </div>
                </div>
            @endif

            <!-- 🏢 SECCIÓN: INFORMACIÓN DETALLADA DEL ÁREA (ESTILO ANITA MORENO) -->
            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Protocolo de Localización y Horario
            </h3>
            <div class="bg-[#12161f] border border-white/10 rounded-[2rem] p-6 mb-10 shadow-2xl">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[8px] font-black text-gray-500 uppercase mb-2 tracking-widest pl-1">Edificio / Bloque</label>
                        <input type="text" name="form_data[building]" value="{{ $task->form_data['building'] ?? '' }}" placeholder="Ej: Edificio G" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-black/40 border-white/5 rounded-xl text-[11px] text-white font-bold h-11 px-4 focus:ring-1 focus:ring-tecsisa-yellow/30 transition disabled:opacity-70">
                    </div>
                    <div>
                        <label class="block text-[8px] font-black text-gray-500 uppercase mb-2 tracking-widest pl-1">Nivel / Piso</label>
                        <input type="text" name="form_data[floor]" value="{{ $task->form_data['floor'] ?? '' }}" placeholder="Ej: Planta Alta" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-black/40 border-white/5 rounded-xl text-[11px] text-white font-bold h-11 px-4 focus:ring-1 focus:ring-tecsisa-yellow/30 transition disabled:opacity-70">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[8px] font-black text-gray-500 uppercase mb-2 tracking-widest pl-1">Área Específica</label>
                        <input type="text" name="form_data[specific_area]" value="{{ $task->form_data['specific_area'] ?? '' }}" placeholder="Ej: Hospitalización" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-black/40 border-white/5 rounded-xl text-[11px] text-white font-bold h-11 px-4 focus:ring-1 focus:ring-tecsisa-yellow/30 transition disabled:opacity-70">
                    </div>
                    <div>
                        <label class="block text-[8px] font-black text-gray-500 uppercase mb-2 tracking-widest pl-1">Sección</label>
                        <input type="text" name="form_data[section]" value="{{ $task->form_data['section'] ?? '' }}" placeholder="Ej: Hemato Oncología" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-black/40 border-white/5 rounded-xl text-[11px] text-white font-bold h-11 px-4 focus:ring-1 focus:ring-tecsisa-yellow/30 transition disabled:opacity-70">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[8px] font-black text-gray-400 uppercase mb-2 tracking-widest pl-1">Hora Inicio</label>
                        <input type="time" name="form_data[start_time]" value="{{ $task->form_data['start_time'] ?? '' }}" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-black/40 border-white/5 rounded-xl text-[11px] text-white font-bold h-11 px-4 focus:ring-1 focus:ring-tecsisa-yellow/30 disabled:opacity-70">
                    </div>
                    <div>
                        <label class="block text-[8px] font-black text-gray-400 uppercase mb-2 tracking-widest pl-1">Hora Término</label>
                        <input type="time" name="form_data[end_time]" value="{{ $task->form_data['end_time'] ?? '' }}" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-black/40 border-white/5 rounded-xl text-[11px] text-white font-bold h-11 px-4 focus:ring-1 focus:ring-tecsisa-yellow/30 disabled:opacity-70">
                    </div>
                </div>
            </div>

            <!-- ✅ SECCIÓN: EVALUACIÓN TÉCNICA (CHECKLIST DINÁMICO) -->
            @php
                $checklist = $task->equipment->system->form_schema['checklist'] ?? [];
            @endphp
            @if(count($checklist) > 0)
            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Evaluación Técnica de Actividades
            </h3>
            <div class="bg-black/40 border border-white/10 rounded-[2rem] overflow-hidden mb-10 shadow-2xl">
                <table class="w-full text-left">
                    <thead class="bg-white/5 border-b border-white/5 text-gray-500 font-black text-[8px] uppercase tracking-widest">
                        <tr>
                            <th class="px-5 py-3 w-1/2">Actividad</th>
                            <th class="px-5 py-3 text-center">Cumple</th>
                            <th class="px-5 py-3">Comentarios</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($checklist as $index => $item)
                        <tr class="group hover:bg-white/[0.02] transition-colors">
                            <td class="px-5 py-3 text-[9px] font-bold text-gray-300 leading-tight">{{ $item }}</td>
                            <td class="px-5 py-3 text-center">
                                <div class="inline-flex bg-black/60 p-1 rounded-lg border border-white/5">
                                    <label class="relative flex items-center justify-center cursor-pointer group/si px-2 py-1 transition rounded-md" :class="evaluations[{{ $index }}] === 'SI' ? 'bg-emerald-500/20' : 'opacity-40'">
                                        <input type="radio" name="form_data[evaluation][{{ $index }}][status]" value="SI" 
                                               x-model="evaluations[{{ $index }}]" class="hidden">
                                        <span class="text-[9px] font-black text-emerald-400 uppercase">SI</span>
                                    </label>
                                    <label class="relative flex items-center justify-center cursor-pointer group/no px-2 py-1 transition rounded-md ml-1" :class="evaluations[{{ $index }}] === 'NO' ? 'bg-red-500/20' : 'opacity-40'">
                                        <input type="radio" name="form_data[evaluation][{{ $index }}][status]" value="NO" 
                                               x-model="evaluations[{{ $index }}]" class="hidden">
                                        <span class="text-[9px] font-black text-red-400 uppercase">NO</span>
                                    </label>
                                </div>
                                <input type="hidden" name="form_data[evaluation][{{ $index }}][item]" value="{{ $item }}">
                            </td>
                            <td class="px-5 py-3">
                                <input type="text" name="form_data[evaluation][{{ $index }}][comment]" 
                                       value="{{ $task->form_data['evaluation'][$index]['comment'] ?? '' }}"
                                       placeholder="..." {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-transparent border-b border-white/10 text-[9px] text-gray-400 focus:text-white transition focus:border-tecsisa-yellow outline-none px-1 h-8 disabled:opacity-70">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- 📸 SECCIÓN: ANEXOS AL INFORME -->
            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2 text-center">
                Anexos Incluidos
            </h3>
            <div class="grid grid-cols-3 gap-3 mb-10">
                <label class="flex flex-col items-center gap-3 p-4 rounded-3xl border border-white/5 bg-[#12161f] cursor-pointer group active:bg-blue-500/10 transition shadow-xl" :class="annexPhoto ? 'border-blue-500/30' : ''">
                    <input type="checkbox" name="form_data[annex_photos]" value="1" x-model="annexPhoto" class="hidden">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition" :class="annexPhoto ? 'bg-blue-500 text-white' : 'bg-white/5 text-gray-600'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-[8px] font-black uppercase text-gray-500 transition group-hover:text-blue-400" :class="annexPhoto ? 'text-blue-400' : ''">Fotos</span>
                </label>
                <label class="flex flex-col items-center gap-3 p-4 rounded-3xl border border-white/5 bg-[#12161f] cursor-pointer group active:bg-orange-500/10 transition shadow-xl" :class="annexPlans ? 'border-orange-500/30' : ''">
                    <input type="checkbox" name="form_data[annex_plans]" value="1" x-model="annexPlans" class="hidden">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition" :class="annexPlans ? 'bg-orange-500 text-white' : 'bg-white/5 text-gray-600'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    </div>
                    <span class="text-[8px] font-black uppercase text-gray-500 transition group-hover:text-orange-400" :class="annexPlans ? 'text-orange-400' : ''">Planos</span>
                </label>
                <label class="flex flex-col items-center gap-3 p-4 rounded-3xl border border-white/5 bg-[#12161f] cursor-pointer group active:bg-emerald-500/10 transition shadow-xl" :class="annexCert ? 'border-emerald-500/30' : ''">
                    <input type="checkbox" name="form_data[annex_cert]" value="1" x-model="annexCert" class="hidden">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition" :class="annexCert ? 'bg-emerald-500 text-white' : 'bg-white/5 text-gray-600'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[8px] font-black uppercase text-gray-500 transition group-hover:text-emerald-400" :class="annexCert ? 'text-emerald-400' : ''">Certific.</span>
                </label>
            </div>

            <!-- 🏁 CONFIRMACIÓN FINAL -->
            <div x-show="status !== 'completed' && status !== 'verified'" class="mt-12 mb-8 bg-tecsisa-yellow/5 border border-tecsisa-yellow/20 rounded-[2.5rem] p-8 flex flex-col items-center text-center shadow-inner">
                <div class="w-16 h-16 bg-tecsisa-yellow text-black rounded-3xl flex items-center justify-center mb-4 shadow-xl shadow-tecsisa-yellow/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="text-sm font-black text-white uppercase tracking-widest mb-2">Finalización de Reporte</h4>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tight max-w-xs mb-6">Al finalizar, este reporte será sellado digitalmente y no podrá ser modificado por el técnico.</p>
                
                <label class="flex items-center gap-4 cursor-pointer group">
                    <div class="relative">
                        <input type="checkbox" x-model="confirmFinal" {{ $isReadOnly ? 'disabled' : '' }} class="hidden">
                        <div class="w-8 h-8 rounded-xl border-2 transition-all flex items-center justify-center" :class="confirmFinal ? 'bg-tecsisa-yellow border-tecsisa-yellow' : 'bg-black/40 border-white/10 group-hover:border-tecsisa-yellow/50'">
                            <svg x-show="confirmFinal" class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <span class="text-xs font-black text-gray-400 group-hover:text-tecsisa-yellow transition-colors uppercase tracking-widest">Confirmo reporte técnico veraz</span>
                </label>
            </div>

            <!-- Barra de Acciones (No fija en móvil para evitar solapamiento con el menú permanente) -->
            <div class="mt-12 bg-[#12161f] border border-white/10 rounded-[2.5rem] p-6 shadow-2xl">
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    @if($task->status !== 'completed' && $task->status !== 'verified')
                    <button type="button" @click="doSubmit('save_draft')" class="w-full sm:w-auto bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold py-4 px-8 rounded-2xl text-[10px] uppercase tracking-[0.2em] transition" :class="{'opacity-50 cursor-not-allowed': isSubmitting}">
                        <span x-show="!isSubmitting">Guardar Borrador</span>
                        <span x-show="isSubmitting">Guardando...</span>
                    </button>
                    
                    <button type="button" @click="doSubmit('submit')" class="w-full sm:w-auto bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black py-4 px-10 rounded-2xl text-[10px] uppercase tracking-[0.2em] shadow-[0_15px_40px_rgba(255,209,0,0.3)] transition active:scale-95 flex items-center justify-center gap-2" :class="{'opacity-50 cursor-not-allowed': isSubmitting}">
                        <span x-show="!isSubmitting">Finalizar Reporte</span>
                        <span x-show="isSubmitting">Enviando Tarea...</span>
                        <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    @else
                    <div class="w-full bg-emerald-500/5 border border-emerald-500/10 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Reporte Sellado Digitalmente</span>
                        </div>
                        <a href="{{ route('tasks.pdf', $task) }}" class="w-full sm:w-auto bg-tecsisa-yellow text-black px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-tecsisa-yellow/20 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Descargar PDF
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('technicianForm', (config) => ({
                isSubmitting: false,
                confirmFinal: config.status === 'completed',
                findings: config.findings,
                materials: config.materials,
                evaluations: {{ json_encode($task->form_data['evaluation'] ?? []) }},
                annexPhoto: {{ isset($task->form_data['annex_photos']) ? 'true' : 'false' }},
                annexPlans: {{ isset($task->form_data['annex_plans']) ? 'true' : 'false' }},
                annexCert: {{ isset($task->form_data['annex_cert']) ? 'true' : 'false' }},
                previews: {
                    before: config.previews.before,
                    after: config.previews.after,
                    fluke_screen: config.previews.fluke_screen,
                    findings: []
                },
                init() {
                    // Initialize evaluations if empty
                    if (Object.keys(this.evaluations).length === 0) {
                        @foreach($checklist as $i => $item)
                        this.evaluations[{{ $i }}] = '';
                        @endforeach
                    }
                    this.findings.forEach((f, i) => {
                        this.previews.findings[i] = f.photo ? config.storagePath + f.photo : '';
                    });
                },
                previewPhoto(event, key, index = null) {
                    const file = event.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        if (index !== null) {
                            this.previews.findings[index] = e.target.result;
                        } else {
                            this.previews[key] = e.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                },
                removePhoto(key, index = null) {
                    if (index !== null) {
                        this.previews.findings[index] = '';
                    } else {
                        this.previews[key] = '';
                    }
                },
                addFinding() {
                    this.findings.push({ photo: null, caption: '' });
                    this.previews.findings.push('');
                },
                removeFinding(index) {
                    if(confirm('¿Seguro de remover este hallazgo?')) {
                        this.findings.splice(index, 1);
                        this.previews.findings.splice(index, 1);
                    }
                },
                addMaterial() {
                    this.materials.push({ name: '', qty: 1 });
                },
                removeMaterial(index) {
                    this.materials.splice(index, 1);
                },
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
            }));
        });
    </script>
    @endpush
</x-technician-layout>
