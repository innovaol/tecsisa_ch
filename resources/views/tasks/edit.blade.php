@php
    $isAdmin = Auth::user()->hasRole('Administrador');
    $isReadOnly = (in_array($task->status, ['completed', 'verified', 'in_review']) && !$isAdmin) 
                  || in_array($task->status, ['completed', 'verified']);
    // $checklist is now injected by TaskController (supports old flat array + new {specs,checklist} format)
    $checklist = $checklist ?? [];
@endphp

<x-technician-layout :hideHeader="true" :hideNav="false">
    <!-- Contextual Header -->
    <div class="fixed top-0 inset-x-0 z-[60] bg-theme-header backdrop-blur-xl border-b border-theme pt-safe transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('tasks.index') }}" class="w-11 h-11 flex items-center justify-center bg-theme/5 border border-theme text-theme-muted hover:text-tecsisa-yellow rounded-2xl transition shadow-md active:scale-90 group shrink-0">
                        <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h1 class="text-xs font-black text-theme uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-tecsisa-yellow animate-pulse"></span> 
                        {{ $isReadOnly ? 'Reporte Finalizado' : 'Edición de Tarea' }}
                    </h1>
                </div>
                <div class="text-[10px] font-black text-theme-muted uppercase tracking-widest hidden sm:block">
                    ID: {{ $task->id }}
                </div>
            </div>
        </div>
    </div>

    @if($isReadOnly)
    <style>
        /* Estilos limpios para la vista de solo lectura (Reporte) */
        input:disabled, 
        textarea:disabled, 
        select:disabled {
            background-color: transparent !important;
            border-color: transparent !important;
            opacity: 1 !important;
            color: var(--theme-text) !important;
            -webkit-text-fill-color: var(--theme-text) !important;
            box-shadow: none !important;
            padding-left: 0 !important;
            font-size: 14px !important;
        }
        
        select:disabled {
            appearance: none !important;
            -webkit-appearance: none !important;
            background-image: none !important;
        }
        
        textarea:disabled {
            padding: 0 !important;
            resize: none !important;
        }

        /* Ocultar bordes de los contenedores de los inputs en solo lectura */
        .bg-theme\/5 {
            background-color: transparent !important;
            border-color: transparent !important;
        }

        /* Ajuste para el checklist y anexos que no usan los inputs de texto normales */
        .cursor-default.pointer-events-none {
            opacity: 1 !important;
        }
    </style>
    @endif

    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto relative z-10">
        <!-- Resumen del Equipo -->
        <div class="bg-theme-card rounded-3xl border border-theme p-5 mb-6 shadow-xl transition-colors duration-500">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-12 h-12 bg-theme/10 rounded-full border border-theme flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-tecsisa-yellow font-mono text-[10px] uppercase font-bold tracking-widest mb-1">{{ $task->equipment->internal_id }}</p>
                        <h2 class="text-sm font-bold text-theme leading-tight mb-1">{{ $task->equipment->name }}</h2>
                        <p class="text-xs text-theme-muted">{{ $task->title }}</p>
                    </div>
                </div>

                @if($isAdmin && !in_array($task->status, ['completed', 'verified']))
                <div class="flex flex-col items-start md:items-end gap-1.5 min-w-[220px]">
                    <label class="text-[8px] font-black text-theme-muted uppercase tracking-[0.2em] opacity-60">Reasignar Responsable</label>
                    <div class="relative w-full group">
                        <select name="assigned_to" @change="doSubmit('reassign')" class="w-full bg-theme/5 border-[1.5px] border-theme-muted/20 rounded-2xl text-[12px] font-black h-12 px-5 appearance-none focus:ring-2 focus:ring-tecsisa-yellow/30 text-theme cursor-pointer pr-12 transition-all hover:border-tecsisa-yellow/40">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }} class="bg-theme-card text-theme">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-theme-muted">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if($isReadOnly)
        <!-- 📝 RESUMEN EJECUTIVO (SOLO LECTURA) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-theme-card border border-theme p-4 rounded-3xl shadow-lg transition-colors duration-500">
                <p class="text-[8px] font-black text-theme-muted uppercase tracking-widest mb-1">Edificio / Bloque</p>
                <p class="text-xs font-bold text-theme">{{ $task->form_data['building'] ?? 'N/A' }}</p>
            </div>
            <div class="bg-theme-card border border-theme p-4 rounded-3xl shadow-lg transition-colors duration-500">
                <p class="text-[8px] font-black text-theme-muted uppercase tracking-widest mb-1">Piso / Nivel</p>
                <p class="text-xs font-bold text-theme">{{ $task->form_data['floor'] ?? 'N/A' }}</p>
            </div>
            <div class="bg-theme-card border border-theme p-4 rounded-3xl shadow-lg transition-colors duration-500">
                <p class="text-[8px] font-black text-theme-muted uppercase tracking-widest mb-1">Área Específica</p>
                <p class="text-xs font-bold text-theme">{{ $task->form_data['specific_area'] ?? 'N/A' }}</p>
            </div>
            <div class="bg-theme-card border border-theme p-4 rounded-3xl shadow-lg transition-colors duration-500">
                <p class="text-[8px] font-black text-theme-muted uppercase tracking-widest mb-1">Horario Laboral</p>
                <p class="text-xs font-bold text-theme">{{ $task->form_data['start_time'] ?? '--' }} - {{ $task->form_data['end_time'] ?? '--' }}</p>
            </div>
        </div>
        @endif

        @if(isset($task->form_data['review_comment']) && $task->status === 'pending')
        <div class="bg-red-500/10 border border-red-500/20 rounded-[2rem] p-6 mb-8 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-red-500/5 rounded-full blur-xl group-hover:bg-red-500/10 transition-all duration-700"></div>
            <h4 class="text-[10px] font-black text-red-500 uppercase tracking-[0.2em] mb-3 flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-red-500/20 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                Motivo del Rechazo / Correcciones Pendientes
            </h4>
            <div class="bg-white/50 dark:bg-black/20 rounded-2xl p-4 border border-red-500/10">
                <p class="text-[11px] font-bold text-red-600 dark:text-red-400 leading-relaxed">{{ $task->form_data['review_comment'] }}</p>
            </div>
        </div>
        @endif

        <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data" 
              x-data="technicianForm({
                  status: '{{ $task->status }}',
                  findings: {{ json_encode(array_values($task->form_data['findings'] ?? [])) }},
                  materials: {{ json_encode(array_values($task->form_data['materials'] ?? [])) }},
                  previews: {
                      before: '{{ isset($task->form_data['photos']['before']) ? asset('storage/' . $task->form_data['photos']['before']) : '' }}',
                      after: '{{ isset($task->form_data['photos']['after']) ? asset('storage/' . $task->form_data['photos']['after']) : '' }}',
                      fluke_screen: '{{ isset($task->form_data['photos']['fluke_screen']) ? asset('storage/' . $task->form_data['photos']['fluke_screen']) : '' }}'
                  },
                  building: '{{ addslashes($task->form_data['building'] ?? "") }}',
                  floor: '{{ addslashes($task->form_data['floor'] ?? "") }}',
                  specific_area: '{{ addslashes($task->form_data['specific_area'] ?? "") }}',
                  section: '{{ addslashes($task->form_data['section'] ?? "") }}',
                  start_time: '{{ $task->form_data['start_time'] ?? "" }}',
                  end_time: '{{ $task->form_data['end_time'] ?? "" }}',
                  storagePath: '{{ asset('storage') }}/'
              })" 
              x-ref="form" id="tech-form" @change="hasChanges = true" @input="hasChanges = true">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" value="save_draft" x-ref="actionField">

            <!-- 📊 SECCIÓN: CONFIGURACIÓN PARA REPORTE SEMANAL (CUADRO DE PRODUCCIÓN) -->
            <h3 class="text-[10px] font-black text-tecsisa-yellow uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Control de Producción / Reporting
            </h3>
            
            <div class="bg-theme-card border border-theme rounded-[2.5rem] p-6 mb-10 shadow-2xl transition-colors duration-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">

                    <!-- Boolean Flags (Checks) -->
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 bg-theme/5 rounded-2xl border border-theme cursor-pointer active:scale-95 transition" :class="hasNewCable ? 'border-tecsisa-yellow/30' : ''">
                            <input type="checkbox" name="has_new_cable" value="1" x-model="hasNewCable" {{ $isReadOnly ? 'disabled' : '' }} class="w-5 h-5 rounded border-theme bg-theme text-tecsisa-yellow focus:ring-tecsisa-yellow">
                            <span class="text-[8px] font-black uppercase text-theme-muted" :class="hasNewCable ? 'text-theme' : ''">Cable Nuevo</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-theme/5 rounded-2xl border border-theme cursor-pointer active:scale-95 transition" :class="hasNewJack ? 'border-tecsisa-yellow/30' : ''">
                            <input type="checkbox" name="has_new_jack" value="1" x-model="hasNewJack" {{ $isReadOnly ? 'disabled' : '' }} class="w-5 h-5 rounded border-theme bg-theme text-tecsisa-yellow focus:ring-tecsisa-yellow">
                            <span class="text-[8px] font-black uppercase text-theme-muted" :class="hasNewJack ? 'text-theme' : ''">Jack Nuevo</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-theme/5 rounded-2xl border border-theme cursor-pointer active:scale-95 transition" :class="hasNewFaceplate ? 'border-tecsisa-yellow/30' : ''">
                            <input type="checkbox" name="has_new_faceplate" value="1" x-model="hasNewFaceplate" {{ $isReadOnly ? 'disabled' : '' }} class="w-5 h-5 rounded border-theme bg-theme text-tecsisa-yellow focus:ring-tecsisa-yellow">
                            <span class="text-[8px] font-black uppercase text-theme-muted" :class="hasNewFaceplate ? 'text-theme' : ''">F. Plate Nuevo</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-theme/5 rounded-2xl border border-theme cursor-pointer active:scale-95 transition" :class="isCertified ? 'border-emerald-500/30' : ''">
                            <input type="checkbox" name="is_certified" value="1" x-model="isCertified" {{ $isReadOnly ? 'disabled' : '' }} class="w-5 h-5 rounded border-theme bg-theme text-emerald-500 focus:ring-emerald-500">
                            <span class="text-[8px] font-black uppercase text-theme-muted" :class="isCertified ? 'text-emerald-400' : ''">Certificado</span>
                        </label>
                    </div>
                </div>
            </div>


            <!-- 📸 SECCIÓN UNIVERSAL: EVIDENCIA VISUAL -->
            @php
                $hasBeforePhoto = !empty($task->form_data['photos']['before']);
                $hasAfterPhoto = !empty($task->form_data['photos']['after']);
                $showEvidencia = !$isReadOnly || $hasBeforePhoto || $hasAfterPhoto;
            @endphp
            
            @if($showEvidencia)
            <h3 class="text-[10px] font-black text-theme-muted uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Registro Fotográfico
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <!-- Foto Inicial -->
                @if(!$isReadOnly || $hasBeforePhoto)
                <div class="bg-theme-card border border-theme p-6 rounded-[2.5rem] flex flex-col items-center text-center shadow-2xl relative overflow-hidden transition-colors duration-500">
                    <span class="text-[9px] uppercase font-black text-theme-muted mb-4 tracking-[0.2em] flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Situación Inicial
                    </span>
                    
                    <div class="w-full relative">
                        <div class="relative w-full aspect-video bg-theme/5 rounded-3xl overflow-hidden flex flex-col items-center justify-center border border-dashed border-theme shadow-inner mb-4 transition-all duration-300" :class="previews.before ? 'border-solid border-tecsisa-yellow/30' : 'border-dashed border-theme'">
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
                                    <label class="flex-1 flex flex-col items-center gap-2 bg-theme/5 border border-theme p-4 rounded-2xl text-theme active:scale-95 transition cursor-pointer">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-[8px] font-black uppercase tracking-widest leading-none">Galería</span>
                                        <input type="file" name="photos[before]" class="hidden" accept="image/*" @change="previewPhoto($event, 'before')">
                                    </label>
                                </div>
                                <label class="hidden md:flex w-full items-center justify-center gap-3 bg-theme/10 border border-theme hover:border-tecsisa-yellow/50 p-4 rounded-2xl text-theme-muted hover:text-tecsisa-yellow transition cursor-pointer group">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="text-xs font-black uppercase tracking-widest leading-none">Seleccionar Archivo</span>
                                    <input type="file" name="photos[before]" class="hidden" accept="image/*" @change="previewPhoto($event, 'before')">
                                </label>
                            </div>
                            <div x-show="previews.before" class="flex gap-3">
                                <button type="button" @click="removePhoto('before')" class="flex-1 h-12 bg-red-500/10 border border-red-500/20 rounded-xl text-red-500 text-[10px] font-black uppercase tracking-widest active:scale-90 transition flex items-center justify-center gap-2">Quitar</button>
                                <button type="button" @click="removePhoto('before')" class="flex-1 h-12 bg-theme/5 border border-theme rounded-xl text-theme text-[10px] font-black uppercase tracking-widest active:scale-90 transition flex items-center justify-center gap-2">Repetir</button>
                            </div>
                            @endunless
                        </div>
                    </div>
                </div>
                @endif

                <!-- Foto Final -->
                @if(!$isReadOnly || $hasAfterPhoto)
                <div class="bg-theme-card border border-theme p-6 rounded-[2.5rem] flex flex-col items-center text-center shadow-2xl relative overflow-hidden transition-colors duration-500">
                    <span class="text-[9px] uppercase font-black text-theme-muted mb-4 tracking-[0.2em] flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Trabajo Finalizado
                    </span>
                    
                    <div class="w-full relative">
                        <div class="relative w-full aspect-video bg-theme/5 rounded-3xl overflow-hidden flex flex-col items-center justify-center border border-dashed border-theme shadow-inner mb-4 transition-all duration-300" :class="previews.after ? 'border-solid border-tecsisa-yellow/30' : 'border-dashed border-theme'">
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
                                    <label class="flex-1 flex flex-col items-center gap-2 bg-theme/5 border border-theme p-4 rounded-2xl text-theme active:scale-95 transition cursor-pointer">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-[8px] font-black uppercase tracking-widest leading-none">Galería</span>
                                        <input type="file" name="photos[after]" class="hidden" accept="image/*" @change="previewPhoto($event, 'after')">
                                    </label>
                                </div>
                                <label class="hidden md:flex w-full items-center justify-center gap-3 bg-theme/10 border border-theme hover:border-tecsisa-yellow/50 p-4 rounded-2xl text-theme-muted hover:text-tecsisa-yellow transition cursor-pointer group">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="text-xs font-black uppercase tracking-widest leading-none">Seleccionar Archivo</span>
                                    <input type="file" name="photos[after]" class="hidden" accept="image/*" @change="previewPhoto($event, 'after')">
                                </label>
                            </div>
                            <div x-show="previews.after" class="flex gap-3">
                                <button type="button" @click="removePhoto('after')" class="flex-1 h-12 bg-red-500/10 border border-red-500/20 rounded-xl text-red-500 text-[10px] font-black uppercase tracking-widest active:scale-90 transition flex items-center justify-center gap-2">Quitar</button>
                                <button type="button" @click="removePhoto('after')" class="flex-1 h-12 bg-theme/5 border border-theme rounded-xl text-theme text-[10px] font-black uppercase tracking-widest active:scale-90 transition flex items-center justify-center gap-2">Repetir</button>
                            </div>
                            @endunless
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- 📸 SECCIÓN: GALERÍA DE HALLAZGOS (HALLAZGOS TÉCNICOS) -->
            @if(!$isReadOnly || count($task->form_data['findings'] ?? []) > 0)
            <h3 class="text-[10px] font-black text-tecsisa-yellow uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Hallazgos Técnicos (Evidencia Detallada)
            </h3>
            <div class="space-y-4 mb-10">
                <template x-for="(f, index) in findings" :key="index">
                    <div class="bg-theme-card border border-theme p-4 rounded-3xl relative shadow-2xl overflow-hidden group transition-colors duration-500">
                        @unless($isReadOnly)
                        <button type="button" @click="removeFinding(index)" class="absolute top-4 right-4 text-red-400/50 hover:text-red-400 transition z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        @endunless
                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-56">
                                <div class="relative w-full aspect-square md:aspect-video bg-theme/5 rounded-2xl border border-dashed border-theme flex flex-col items-center justify-center shadow-inner relative overflow-hidden transition-all duration-300" :class="previews.findings[index] ? 'border-solid border-tecsisa-yellow/30' : 'border-dashed border-theme'">
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
                                        <label class="flex-1 flex flex-col items-center gap-2 bg-theme/5 border border-theme p-3 rounded-xl text-theme active:scale-95 transition cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-[7px] font-black uppercase tracking-widest leading-none">Galería</span>
                                            <input type="file" :name="'finding_photos['+index+']'" class="hidden" accept="image/*" @change="previewPhoto($event, 'findings', index)">
                                        </label>
                                    </div>
                                    
                                    <!-- PC Control -->
                                    <label x-show="!previews.findings[index]" class="hidden md:flex flex-col items-center gap-2 text-theme-muted hover:text-tecsisa-yellow transition cursor-pointer bg-theme/10 border border-theme p-4 rounded-xl">
                                        <svg class="w-6 h-6 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <span class="text-[8px] font-black uppercase tracking-widest">Añadir Archivo</span>
                                        <input type="file" :name="'finding_photos['+index+']'" class="hidden" accept="image/*" @change="previewPhoto($event, 'findings', index)">
                                    </label>

                                    <!-- Action Buttons if Photo Exists -->
                                    <div x-show="previews.findings[index]" class="flex gap-2">
                                        <button type="button" @click="removePhoto('findings', index)" class="flex-1 h-10 bg-red-500/10 border border-red-500/20 rounded-lg text-red-500 text-[8px] font-black uppercase active:scale-90 transition">Quitar</button>
                                        <button type="button" @click="removePhoto('findings', index)" class="flex-1 h-10 bg-theme/5 border border-theme rounded-lg text-theme text-[8px] font-black uppercase active:scale-90 transition">Repetir</button>
                                    </div>
                                    @endunless
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <label class="block text-[8px] font-black text-theme-muted uppercase tracking-widest mb-1.5 px-1">Leyenda / Hallazgo observado</label>
                                <textarea :name="'finding_captions['+index+']'" x-model="f.caption" rows="2" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-theme/5 border border-theme rounded-2xl text-[10px] font-bold text-theme p-4 leading-relaxed focus:ring-1 focus:ring-tecsisa-yellow/30 placeholder:text-theme-muted transition disabled:opacity-70 disabled:cursor-not-allowed" placeholder="{{ $isReadOnly ? '' : 'Describa el detalle observado en sitio...' }}"></textarea>
                                <input type="hidden" :name="'finding_paths['+index+']'" :value="f.photo">
                            </div>
                        </div>
                    </div>
                </template>
                
                @unless($isReadOnly)
                <button type="button" @click="addFinding()" class="w-full h-16 bg-theme/5 border border-dashed border-theme rounded-3xl flex items-center justify-center gap-3 text-theme-muted hover:text-tecsisa-yellow hover:border-tecsisa-yellow/30 active:scale-95 transition duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span class="text-[9px] font-black uppercase tracking-[0.2em]">Añadir Nuevo Hallazgo</span>
                </button>
                @endunless
            </div>
            @endif

            <!-- 🛠️ SECCIÓN: INSUMOS Y MATERIALES UTILIZADOS -->
            @if(!$isReadOnly || count($task->form_data['materials'] ?? []) > 0)
            <h3 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Materiales e Insumos Utilizados
            </h3>
            <div class="space-y-3 mb-12">
                <div class="bg-theme-card rounded-3xl border border-theme p-4 transition-colors duration-500">
                    <template x-for="(m, index) in materials" :key="index">
                        <div class="flex items-center gap-3 mb-3 animate-fadeIn">
                            <div class="flex-1">
                                <input type="text" name="material_names[]" x-model="m.name" placeholder="Ej: Metros de Cable CAT6" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-theme/5 border border-theme rounded-xl text-[10px] text-theme font-bold h-11 px-4 focus:ring-1 focus:ring-emerald-500/30 disabled:opacity-70 transition-colors">
                            </div>
                            <div class="w-24">
                                <input type="number" name="material_qtys[]" x-model="m.qty" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-theme/5 border border-theme rounded-xl text-[10px] text-theme font-bold h-11 px-2 text-center focus:ring-1 focus:ring-emerald-500/30 disabled:opacity-70 transition-colors">
                            </div>
                            @unless($isReadOnly)
                            <button type="button" @click="removeMaterial(index)" class="text-red-400 p-2 hover:bg-red-500/10 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            @endunless
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
            @endif

            <!-- 📊 SECCIÓN ESPECÍFICA: VOZ Y DATOS (FLUKE) -->
            @if($task->equipment->system_id == 2)
            <h3 class="text-[10px] font-black text-blue-500 uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Certificación Fluke Networks
            </h3>
            <div class="space-y-4 mb-8">
                <div class="bg-theme-card border border-theme p-5 rounded-3xl shadow-xl transition-colors duration-500">
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
                                        <label class="flex-1 flex items-center justify-center gap-2 bg-theme/5 border border-theme h-12 rounded-xl text-theme active:scale-95 transition cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-[8px] font-black uppercase tracking-widest leading-none">Galería</span>
                                            <input type="file" name="photos[fluke_screen]" class="hidden" accept="image/*" @change="previewPhoto($event, 'fluke_screen')">
                                        </label>
                                    </div>
                                    <!-- PC -->
                                    <label class="hidden md:flex flex-1 items-center justify-center gap-3 bg-theme/10 border border-theme hover:border-tecsisa-yellow/50 h-12 rounded-xl text-theme-muted hover:text-tecsisa-yellow transition cursor-pointer group px-4">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Seleccionar Archivo</span>
                                        <input type="file" name="photos[fluke_screen]" class="hidden" accept="image/*" @change="previewPhoto($event, 'fluke_screen')">
                                    </label>
                                </div>
                                
                                <!-- Estado: Con Foto -->
                                <div x-show="previews.fluke_screen" class="flex gap-3">
                                    <button type="button" @click="removePhoto('fluke_screen')" class="flex-1 h-12 bg-red-500/10 border border-red-500/20 rounded-xl text-red-500 text-[10px] font-black uppercase tracking-widest active:scale-90 transition flex items-center justify-center gap-2">Quitar</button>
                                    <button type="button" @click="removePhoto('fluke_screen')" class="flex-1 h-12 bg-theme/5 border border-theme rounded-xl text-theme text-[10px] font-black uppercase tracking-widest active:scale-90 transition flex items-center justify-center gap-2">Repetir</button>
                                </div>
                                @endunless
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-theme/5 p-4 rounded-2xl border border-theme">
                            <label class="block text-[8px] font-black text-theme-muted uppercase mb-1 tracking-widest text-center">Margen (dB)</label>
                            <input type="text" name="form_data[fluke_margin]" value="{{ $task->form_data['fluke_margin'] ?? '' }}" placeholder="Ej: 6.4" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-transparent text-theme font-mono font-bold text-center outline-none border-none p-0 disabled:opacity-70">
                        </div>
                        <div class="bg-theme/5 p-4 rounded-2xl border border-theme">
                            <label class="block text-[8px] font-black text-theme-muted uppercase mb-1 tracking-widest text-center">Longitud (m)</label>
                            <input type="text" name="form_data[fluke_length]" value="{{ $task->form_data['fluke_length'] ?? '' }}" placeholder="Ej: 42.1" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-transparent text-theme font-mono font-bold text-center outline-none border-none p-0 disabled:opacity-70">
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
                <div class="bg-theme-card border border-theme rounded-3xl p-5 mb-8 space-y-4 shadow-xl transition-colors duration-500">
                    <label class="flex items-center justify-between p-3 bg-theme/5 rounded-2xl border border-theme group active:bg-tecsisa-yellow/5">
                        <span class="text-xs font-bold text-theme-muted group-hover:text-theme transition">Limpieza Física y Soplado</span>
                        <input type="checkbox" name="form_data[maint_clean]" value="1" {{ ($task->form_data['maint_clean'] ?? false) ? 'checked' : '' }} {{ $isReadOnly ? 'disabled' : '' }} class="w-5 h-5 rounded border-theme bg-theme text-tecsisa-yellow focus:ring-tecsisa-yellow disabled:opacity-50 transition-colors">
                    </label>
                    <label class="flex items-center justify-between p-3 bg-theme/5 rounded-2xl border border-theme group active:bg-tecsisa-yellow/5">
                        <span class="text-xs font-bold text-theme-muted group-hover:text-theme transition">Ajuste de Conectores / Peinado</span>
                        <input type="checkbox" name="form_data[maint_cables]" value="1" {{ ($task->form_data['maint_cables'] ?? false) ? 'checked' : '' }} {{ $isReadOnly ? 'disabled' : '' }} class="w-5 h-5 rounded border-theme bg-theme text-tecsisa-yellow focus:ring-tecsisa-yellow disabled:opacity-50 transition-colors">
                    </label>
                    <label class="flex items-center justify-between p-3 bg-theme/5 rounded-2xl border border-theme group active:bg-tecsisa-yellow/5">
                        <span class="text-xs font-bold text-theme-muted group-hover:text-theme transition">Verificación de Etiquetado</span>
                        <input type="checkbox" name="form_data[maint_tags]" value="1" {{ ($task->form_data['maint_tags'] ?? false) ? 'checked' : '' }} {{ $isReadOnly ? 'disabled' : '' }} class="w-5 h-5 rounded border-theme bg-theme text-tecsisa-yellow focus:ring-tecsisa-yellow disabled:opacity-50 transition-colors">
                    </label>
                </div>

            @elseif($task->task_type === 'replacement')
                <h3 class="text-[10px] font-black text-red-500 uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    Control de Sustitución
                </h3>
                <div class="bg-red-500/5 border border-red-500/10 rounded-3xl p-5 mb-8 space-y-4">
                    <div class="bg-theme/5 p-3 rounded-2xl border border-theme">
                        <label class="block text-[8px] font-black text-red-400 uppercase mb-1 tracking-tighter">S/N Equipo Entrante</label>
                        <input type="text" name="form_data[new_serial]" value="{{ $task->form_data['new_serial'] ?? '' }}" placeholder="Escanea serial nuevo..." {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-transparent text-theme font-mono font-bold text-sm outline-none border-none p-0 disabled:opacity-70">
                    </div>
                </div>

            @elseif($task->task_type === 'installation')
                <h3 class="text-[10px] font-black text-cyan-500 uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Aceptación de Instalación
                </h3>
                <div class="bg-cyan-500/5 border border-cyan-500/10 rounded-3xl p-5 mb-8 space-y-4">
                    <div class="bg-theme/5 p-4 rounded-2xl border border-theme">
                        <label class="block text-[9px] font-black text-cyan-400 uppercase mb-2">Prueba de Continuidad / Link</label>
                        <select name="form_data[install_test]" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-transparent text-theme text-xs font-bold outline-none border-none p-0 uppercase disabled:opacity-70">
                            <option value="PASS" {{ ($task->form_data['install_test'] ?? '') == 'PASS' ? 'selected' : '' }}>Prueba Exitosa (PASS) ✓</option>
                            <option value="FAIL" {{ ($task->form_data['install_test'] ?? '') == 'FAIL' ? 'selected' : '' }}>Prueba Fallida (FAIL) ✗</option>
                        </select>
                    </div>
                </div>
            @endif

            <!-- 🏢 SECCIÓN: INFORMACIÓN DETALLADA DEL ÁREA (ESTILO ANITA MORENO) -->
            <h3 class="text-[10px] font-black text-theme-muted uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Protocolo de Localización y Horario
            </h3>
            <div class="bg-theme-card border border-theme rounded-[2rem] p-6 mb-10 shadow-2xl transition-colors duration-500">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[8px] font-black text-theme-muted uppercase mb-2 tracking-widest pl-1">Edificio / Bloque <span class="text-red-500">*</span></label>
                        <input type="text" name="form_data[building]" x-model="building" placeholder="Ej: Edificio G" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-theme/5 border border-theme rounded-xl text-[11px] text-theme font-bold h-11 px-4 focus:ring-1 focus:ring-tecsisa-yellow/30 transition disabled:opacity-70">
                    </div>
                    <div>
                        <label class="block text-[8px] font-black text-theme-muted uppercase mb-2 tracking-widest pl-1">Nivel / Piso <span class="text-red-500">*</span></label>
                        <input type="text" name="form_data[floor]" x-model="floor" placeholder="Ej: Planta Alta" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-theme/5 border border-theme rounded-xl text-[11px] text-theme font-bold h-11 px-4 focus:ring-1 focus:ring-tecsisa-yellow/30 transition disabled:opacity-70">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[8px] font-black text-theme-muted uppercase mb-2 tracking-widest pl-1">Área Específica <span class="text-red-500">*</span></label>
                        <input type="text" name="form_data[specific_area]" x-model="specific_area" placeholder="Ej: Hospitalización" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-theme/5 border border-theme rounded-xl text-[11px] text-theme font-bold h-11 px-4 focus:ring-1 focus:ring-tecsisa-yellow/30 transition disabled:opacity-70">
                    </div>
                    <div>
                        <label class="block text-[8px] font-black text-theme-muted uppercase mb-2 tracking-widest pl-1">Sección</label>
                        <input type="text" name="form_data[section]" x-model="section" placeholder="Ej: Hemato Oncología" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-theme/5 border border-theme rounded-xl text-[11px] text-theme font-bold h-11 px-4 focus:ring-1 focus:ring-tecsisa-yellow/30 transition disabled:opacity-70">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[8px] font-black text-theme-muted uppercase mb-2 tracking-widest pl-1">Hora Inicio</label>
                        <input type="time" name="form_data[start_time]" x-model="start_time" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-theme/5 border border-theme rounded-xl text-[11px] text-theme font-bold h-11 px-4 focus:ring-1 focus:ring-tecsisa-yellow/30 transition-colors disabled:opacity-70">
                    </div>
                    <div>
                        <label class="block text-[8px] font-black text-theme-muted uppercase mb-2 tracking-widest pl-1">Hora Término</label>
                        <input type="time" name="form_data[end_time]" x-model="end_time" {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-theme/5 border border-theme rounded-xl text-[11px] text-theme font-bold h-11 px-4 focus:ring-1 focus:ring-tecsisa-yellow/30 transition-colors disabled:opacity-70">
                    </div>
                </div>
            </div>

            <!-- ✅ SECCIÓN: EVALUACIÓN TÉCNICA (CHECKLIST DINÁMICO) -->
            @if(count($checklist) > 0)
            <h3 class="text-[10px] font-black text-theme uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Evaluación Técnica de Actividades
            </h3>
            <div class="bg-theme-card border border-theme rounded-[2rem] overflow-hidden mb-10 shadow-2xl transition-colors duration-500">
                <table class="w-full text-left">
                    <thead class="bg-theme/5 border-b border-theme text-theme-muted font-black text-[8px] uppercase tracking-widest">
                        <tr>
                            <th class="px-5 py-3 w-1/2">Actividad</th>
                            <th class="px-5 py-3 text-center">Cumple</th>
                            <th class="px-5 py-3">Comentarios</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y border-theme">
                        @foreach($checklist as $index => $item)
                        <tr class="group hover:bg-theme/5 transition-colors">
                            <td class="px-5 py-3 text-[9px] font-bold text-theme-muted leading-tight">{{ $item }}</td>
                            <td class="px-5 py-3 text-center">
                                <div class="inline-flex bg-theme/5 p-1 rounded-lg border border-theme">
                                    <label class="relative flex items-center justify-center group/si px-2 py-1 transition rounded-md {{ $isReadOnly ? 'cursor-default pointer-events-none' : 'cursor-pointer' }}" :class="evaluations[{{ $index }}] === 'SI' ? 'bg-emerald-500/20' : 'opacity-40'">
                                        <input type="radio" name="form_data[evaluation][{{ $index }}][status]" value="SI" 
                                               x-model="evaluations[{{ $index }}]" class="hidden" {{ $isReadOnly ? 'disabled' : '' }}>
                                        <span class="text-[9px] font-black text-emerald-400 uppercase">SI</span>
                                    </label>
                                    <label class="relative flex items-center justify-center group/no px-2 py-1 transition rounded-md ml-1 {{ $isReadOnly ? 'cursor-default pointer-events-none' : 'cursor-pointer' }}" :class="evaluations[{{ $index }}] === 'NO' ? 'bg-red-500/20' : 'opacity-40'">
                                        <input type="radio" name="form_data[evaluation][{{ $index }}][status]" value="NO" 
                                               x-model="evaluations[{{ $index }}]" class="hidden" {{ $isReadOnly ? 'disabled' : '' }}>
                                        <span class="text-[9px] font-black text-red-400 uppercase">NO</span>
                                    </label>
                                </div>
                                <input type="hidden" name="form_data[evaluation][{{ $index }}][item]" value="{{ $item }}">
                            </td>
                            <td class="px-5 py-3">
                                <input type="text" name="form_data[evaluation][{{ $index }}][comment]" 
                                       value="{{ $task->form_data['evaluation'][$index]['comment'] ?? '' }}"
                                       placeholder="..." {{ $isReadOnly ? 'disabled' : '' }} class="w-full bg-transparent border-b border-theme text-[9px] text-theme-muted focus:text-theme transition focus:border-tecsisa-yellow outline-none px-1 h-8 disabled:opacity-70">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <h3 class="text-[10px] font-black text-theme uppercase tracking-[0.3em] mb-4 text-center">
                Anexos Incluidos
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
                <!-- Anexo: Registro Fotográfico (Este es el que ya tenemos arriba, pero aquí se confirma) -->
                <div class="flex flex-col gap-2">
                    <label class="flex items-center gap-3 p-4 rounded-3xl border border-theme bg-theme-card group transition shadow-xl transition-colors duration-500 {{ $isReadOnly ? 'cursor-default pointer-events-none opacity-80' : 'cursor-pointer active:bg-blue-500/10' }}" :class="annexPhoto ? 'border-blue-500/30' : ''">
                        <input type="checkbox" name="form_data[annex_photos]" value="1" x-model="annexPhoto" class="hidden" {{ $isReadOnly ? 'disabled' : '' }}>
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition" :class="annexPhoto ? 'bg-blue-500 text-white' : 'bg-theme/5 text-theme-muted'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-[8px] font-black uppercase text-theme-muted transition group-hover:text-blue-400" :class="annexPhoto ? 'text-blue-400' : ''">Fotos Incluidas</span>
                    </label>
                </div>

                <!-- Anexo: Planos de Ubicación -->
                <div class="flex flex-col gap-2">
                    <label class="flex items-center gap-3 p-4 rounded-3xl border border-theme bg-theme-card group transition shadow-xl transition-colors duration-500 {{ $isReadOnly ? 'cursor-default pointer-events-none opacity-80' : 'cursor-pointer active:bg-orange-500/10' }}" :class="annexPlans ? 'border-orange-500/30' : ''">
                        <input type="checkbox" name="form_data[annex_plans]" value="1" x-model="annexPlans" class="hidden" {{ $isReadOnly ? 'disabled' : '' }}>
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition" :class="annexPlans ? 'bg-orange-500 text-white' : 'bg-theme/5 text-theme-muted'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        </div>
                        <span class="text-[8px] font-black uppercase text-theme-muted transition group-hover:text-orange-400" :class="annexPlans ? 'text-orange-400' : ''">Planos de Red</span>
                    </label>
                    <div x-show="annexPlans" class="px-2 space-y-3">
                        @if(!$isReadOnly)
                            <div class="space-y-2">
                                <label class="block text-[8px] font-black text-theme-muted uppercase tracking-widest pl-1">Añadir Archivos</label>
                                <input type="file" name="annex_file_plans[]" multiple x-ref="plansInput" @change="updatePending($event, 'plans')" class="text-[10px] text-theme-muted file:bg-theme/10 file:border-none file:rounded-lg file:text-theme file:text-[9px] file:font-black file:uppercase file:px-3 file:py-1 cursor-pointer w-full">
                                
                                <template x-if="pendingPlans.length > 0">
                                    <div class="mt-2 space-y-1">
                                        <p class="text-[7px] font-black text-tecsisa-yellow uppercase tracking-widest px-1">Pendientes por subir:</p>
                                        <template x-for="(name, i) in pendingPlans" :key="i">
                                            <div class="flex items-center justify-between p-2 bg-tecsisa-yellow/5 rounded-xl border border-tecsisa-yellow/20">
                                                <div class="flex items-center gap-2 truncate">
                                                    <svg class="w-3 h-3 text-tecsisa-yellow shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span class="text-[8px] font-bold text-tecsisa-yellow truncate pr-2" x-text="name"></span>
                                                </div>
                                                <button type="button" @click="removePending('plans', i)" class="text-tecsisa-yellow hover:text-red-500 transition-colors p-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        @endif
                        
                        <div class="space-y-2" x-show="plans.length > 0">
                            <p class="text-[7px] font-black text-theme-muted uppercase tracking-widest pl-1" x-show="plans.length > 0">Archivos en el servidor:</p>
                            <template x-for="(file, i) in plans" :key="i">
                                <div class="flex items-center justify-between p-2 bg-theme/5 rounded-xl border border-theme/10">
                                    <a :href="'{{ asset('storage') }}/' + file" target="_blank" class="text-[9px] font-black text-orange-400 uppercase truncate pr-2 hover:underline" x-text="file.split('/').pop()"></a>
                                    @unless($isReadOnly)
                                    <button type="button" @click="removeExisting('plans', file)" class="text-red-400 hover:scale-110 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    @else
                                    <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg>
                                    @endunless
                                </div>
                            </template>
                        </div>

                        {{-- Inputs para el controlador --}}
                        <template x-for="path in plansToRemove">
                            <input type="hidden" name="plans_to_remove[]" :value="path">
                        </template>
                    </div>
                </div>

                <!-- Anexo: Certificaciones -->
                <div class="flex flex-col gap-2">
                    <label class="flex items-center gap-3 p-4 rounded-3xl border border-theme bg-theme-card group transition shadow-xl transition-colors duration-500 {{ $isReadOnly ? 'cursor-default pointer-events-none opacity-80' : 'cursor-pointer active:bg-emerald-500/10' }}" :class="annexCert ? 'border-emerald-500/30' : ''">
                        <input type="checkbox" name="form_data[annex_cert]" value="1" x-model="annexCert" class="hidden" {{ $isReadOnly ? 'disabled' : '' }}>
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition" :class="annexCert ? 'bg-emerald-500 text-white' : 'bg-theme/5 text-theme-muted'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-[8px] font-black uppercase text-theme-muted transition group-hover:text-emerald-400" :class="annexCert ? 'text-emerald-400' : ''">Certificaciones</span>
                    </label>
                    <div x-show="annexCert" class="px-2 space-y-3">
                        @if(!$isReadOnly)
                            <div class="space-y-2">
                                <label class="block text-[8px] font-black text-theme-muted uppercase tracking-widest pl-1">Añadir Archivos</label>
                                <input type="file" name="annex_file_cert[]" multiple x-ref="certsInput" @change="updatePending($event, 'certs')" class="text-[10px] text-theme-muted file:bg-theme/10 file:border-none file:rounded-lg file:text-theme file:text-[9px] file:font-black file:uppercase file:px-3 file:py-1 cursor-pointer w-full">
                                
                                <template x-if="pendingCerts.length > 0">
                                    <div class="mt-2 space-y-1">
                                        <p class="text-[7px] font-black text-tecsisa-yellow uppercase tracking-widest px-1">Pendientes por subir:</p>
                                        <template x-for="(name, i) in pendingCerts" :key="i">
                                            <div class="flex items-center justify-between p-2 bg-tecsisa-yellow/5 rounded-xl border border-tecsisa-yellow/20">
                                                <div class="flex items-center gap-2 truncate">
                                                    <svg class="w-3 h-3 text-tecsisa-yellow shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span class="text-[8px] font-bold text-tecsisa-yellow truncate pr-2" x-text="name"></span>
                                                </div>
                                                <button type="button" @click="removePending('certs', i)" class="text-tecsisa-yellow hover:text-red-500 transition-colors p-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        @endif

                        <div class="space-y-2" x-show="certs.length > 0">
                            <p class="text-[7px] font-black text-theme-muted uppercase tracking-widest pl-1" x-show="certs.length > 0">Archivos en el servidor:</p>
                            <template x-for="(file, i) in certs" :key="i">
                                <div class="flex items-center justify-between p-2 bg-theme/5 rounded-xl border border-theme/10">
                                    <a :href="'{{ asset('storage') }}/' + file" target="_blank" class="text-[9px] font-black text-emerald-400 uppercase truncate pr-2 hover:underline" x-text="file.split('/').pop()"></a>
                                    @unless($isReadOnly)
                                    <button type="button" @click="removeExisting('certs', file)" class="text-red-400 hover:scale-110 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    @else
                                    <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg>
                                    @endunless
                                </div>
                            </template>
                        </div>

                        {{-- Inputs para el controlador --}}
                        <template x-for="path in certsToRemove">
                            <input type="hidden" name="certs_to_remove[]" :value="path">
                        </template>
                    </div>
                </div>
            </div>


            <!-- Barra de Acciones (No fija en móvil para evitar solapamiento con el menú permanente) -->
            <div class="mt-12 bg-theme-card border border-theme rounded-[2.5rem] p-8 shadow-2xl transition-colors duration-500">
                <div class="flex flex-col sm:flex-row gap-5 justify-center items-center w-full">
                    @if($task->status !== 'completed' && $task->status !== 'verified')
                        @php
                            $isRecentlyCreated = $task->created_at->diffInMinutes(now()) < 30;
                            $hasNoProgress = !$task->started_at && empty($task->form_data['findings']) && empty($task->form_data['materials']);
                            $isNewTask = $isRecentlyCreated && $hasNoProgress;
                        @endphp

                        @if($isAdmin && $task->status === 'in_review')
                            <div class="flex-1 w-full">
                                <label class="block text-[8px] font-black text-theme-muted uppercase mb-1.5 tracking-widest pl-1">Motivo del Rechazo / Comentarios <span class="text-red-500">*</span></label>
                                <textarea name="review_comment" placeholder="Describe claramente qué debe corregir el técnico..." class="w-full bg-theme/5 border border-theme rounded-xl text-[11px] text-theme p-4 focus:ring-1 focus:ring-tecsisa-yellow/30 transition shadow-inner h-24 resize-none"></textarea>
                            </div>

                            <button type="button" @click="doSubmit('reject')" class="w-full sm:w-auto bg-red-500/10 border border-red-500/20 text-red-500 font-bold py-4 px-8 rounded-2xl text-[10px] uppercase tracking-widest transition hover:bg-red-500/20 hover:scale-[1.02] active:scale-95">
                                Rechazar
                            </button>

                            <button type="button" @click="doSubmit('approve')" class="w-full sm:w-auto bg-emerald-500 hover:bg-emerald-600 text-white font-black py-4 px-10 rounded-2xl text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-emerald-500/20 transition active:scale-95 flex items-center justify-center gap-2">
                                Aprobar
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        @elseif($task->status === 'in_review')
                            <div class="w-full bg-blue-500/5 border border-blue-500/10 rounded-2xl p-4 flex items-center justify-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                                <span class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Reporte Pendiente de Aprobación por el Administrador</span>
                            </div>
                        @else
                            @if($isNewTask)
                                <button type="button" @click="document.getElementById('cancel-form').submit();" class="w-full sm:w-auto text-[10px] font-black text-gray-500 hover:text-red-400 uppercase tracking-widest px-6 py-4 transition-colors order-last sm:order-first">
                                    Cancelar
                                </button>
                            @else
                                <a href="{{ route('tasks.index') }}" class="w-full sm:w-auto text-center text-[10px] font-black text-gray-500 hover:text-theme uppercase tracking-widest px-6 py-4 transition-colors order-last sm:order-first">
                                    Volver a Tareas
                                </a>

                                @if($task->status === 'draft')
                                <button type="button" @click="if(confirm('¿Deseas eliminar este borrador y su información?')) document.getElementById('cancel-form').submit();" class="w-full sm:w-auto text-[10px] font-black text-gray-500 hover:text-red-400 uppercase tracking-widest px-6 py-4 transition-colors order-last sm:order-first">
                                    Eliminar
                                </button>
                                @endif
                            @endif

                            <button type="button" @click="doSubmit('save_draft')" class="w-full sm:w-auto bg-gray-100 dark:bg-theme/10 hover:bg-gray-200 dark:hover:bg-theme/20 border border-theme text-gray-800 dark:text-theme font-bold py-4 px-8 rounded-2xl text-[10px] uppercase tracking-[0.2em] transition" :class="{'opacity-50 cursor-not-allowed': isSubmitting}">
                                <span x-show="!isSubmitting">Guardar</span>
                                <span x-show="isSubmitting">Guardando...</span>
                            </button>
                            
                            <div class="relative group w-full sm:w-auto">
                                <button type="button" 
                                        :disabled="!canFinalize || isSubmitting"
                                        @click="doSubmit('submit')" 
                                        class="w-full sm:w-auto font-black py-4 px-10 rounded-2xl text-[10px] uppercase tracking-widest transition active:scale-90 flex items-center justify-center gap-2" 
                                        :class="canFinalize && !isSubmitting ? 'bg-tecsisa-yellow hover:bg-yellow-400 text-black shadow-[0_15px_40px_rgba(255,209,0,0.3)]' : 'bg-gray-300 dark:bg-gray-800 text-gray-500 cursor-not-allowed opacity-50'">
                                    <span x-show="!isSubmitting">Guardar y Finalizar</span>
                                    <span x-show="isSubmitting">Procesando...</span>
                                    <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                                
                                <!-- Tooltip de Requisitos -->
                                <div x-show="!canFinalize" 
                                     class="absolute bottom-full left-1/2 -translate-x-1/2 mb-4 w-64 p-4 bg-red-600 text-white text-[9px] font-bold uppercase tracking-widest rounded-2xl shadow-2xl opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-[100] border border-red-500">
                                     <div class="flex flex-col gap-2">
                                        <span class="text-white border-b border-red-400 pb-1 mb-1">Requisitos Pendientes:</span>
                                        <template x-for="error in validationErrors">
                                            <div class="flex items-center gap-2">
                                                <span class="w-1 h-1 bg-white rounded-full"></span>
                                                <span x-text="error"></span>
                                            </div>
                                        </template>
                                     </div>
                                     <div class="absolute top-full left-1/2 -translate-x-1/2 border-8 border-transparent border-t-red-600"></div>
                                </div>

                                <!-- Botón flotante de exclamación -->
                                <div x-show="!canFinalize" 
                                     class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-black shadow-lg animate-bounce pointer-events-none">!</div>
                            </div>
                        @endif
                    @else
                        <div class="w-full bg-emerald-500/5 border border-emerald-500/10 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Reporte Sellado Digitalmente</span>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                                <a href="{{ route('tasks.index') }}" class="w-full sm:w-auto text-center text-[10px] font-black text-gray-500 hover:text-theme uppercase tracking-widest px-6 py-3 transition-colors">
                                    Volver
                                </a>
                                <a href="{{ route('tasks.pdf', $task) }}" target="_blank" class="w-full sm:w-auto bg-tecsisa-yellow hover:bg-yellow-400 text-black px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-tecsisa-yellow/20 flex items-center justify-center gap-2 transition active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    PDF
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </form>

        {{-- Hidden form for cancellation/deletion --}}
        <form id="cancel-form" action="{{ route('tasks.destroy', $task) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    @push('scripts')
    <script>
        const initTechnicianForm = () => {
            Alpine.data('technicianForm', (config) => ({
                isSubmitting: false,
                hasChanges: false,
                confirmFinal: true,
                findings: config.findings,
                materials: config.materials,
                evaluations: {},
                hasNewCable: {{ $task->has_new_cable ? 'true' : 'false' }},
                hasNewJack: {{ $task->has_new_jack ? 'true' : 'false' }},
                hasNewFaceplate: {{ $task->has_new_faceplate ? 'true' : 'false' }},
                isCertified: {{ $task->is_certified ? 'true' : 'false' }},
                annexPhoto: {{ isset($task->form_data['annex_photos']) ? 'true' : 'false' }},
                annexPlans: {{ isset($task->form_data['annex_plans']) ? 'true' : 'false' }},
                annexCert: {{ isset($task->form_data['annex_cert']) ? 'true' : 'false' }},
                previews: {
                    before: config.previews.before,
                    after: config.previews.after,
                    fluke_screen: config.previews.fluke_screen,
                    findings: []
                },
                building: config.building,
                floor: config.floor,
                specific_area: config.specific_area,
                section: config.section,
                start_time: config.start_time,
                end_time: config.end_time,
                plans: {!! json_encode($task->form_data['files']['plans'] ?? []) !!},
                certs: {!! json_encode($task->form_data['files']['certs'] ?? []) !!},
                plansToRemove: [],
                certsToRemove: [],
                pendingPlans: [],
                pendingCerts: [],
                removeExisting(type, path) {
                    if (type === 'plans') {
                        this.plans = this.plans.filter(p => p !== path);
                        if (!this.plansToRemove.includes(path)) this.plansToRemove.push(path);
                    } else {
                        this.certs = this.certs.filter(c => c !== path);
                        if (!this.certsToRemove.includes(path)) this.certsToRemove.push(path);
                    }
                },
                updatePending(event, type) {
                    const files = Array.from(event.target.files);
                    if (type === 'plans') {
                        this.pendingPlans = files.map(f => f.name);
                    } else {
                        this.pendingCerts = files.map(f => f.name);
                    }
                },
                removePending(type, index) {
                    const input = type === 'plans' ? this.$refs.plansInput : this.$refs.certsInput;
                    const dt = new DataTransfer();
                    const { files } = input;
                    for (let i = 0; i < files.length; i++) {
                        if (i !== index) dt.items.add(files[i]);
                    }
                    input.files = dt.files;
                    // Trigger update manually
                    const event = { target: input };
                    this.updatePending(event, type);
                },
                clearPending(type) {
                    if (type === 'plans') {
                        this.pendingPlans = [];
                        this.$refs.plansInput.value = '';
                    } else {
                        this.pendingCerts = [];
                        this.$refs.certsInput.value = '';
                    }
                },
                init() {
                    let rawEvals = {!! json_encode($task->form_data['evaluation'] ?? []) !!};
                    this.evaluations = {};
                    
                    // Populate evaluations safely
                    if (rawEvals && typeof rawEvals === 'object') {
                        Object.keys(rawEvals).forEach(k => {
                            this.evaluations[k] = (typeof rawEvals[k] === 'object') ? (rawEvals[k].status || '') : rawEvals[k];
                        });
                    }

                    // Default values for missing checklist items
                    @foreach($checklist as $i => $item)
                        if (this.evaluations[{{ $i }}] === undefined) {
                            this.evaluations[{{ $i }}] = '';
                        }
                    @endforeach

                    // Findings Previews
                    if (Array.isArray(this.findings)) {
                        this.findings.forEach((f, i) => {
                            if (f && f.photo) {
                                // If it already looks like a URL (data: or http), use it, otherwise add storage path
                                this.previews.findings[i] = (f.photo.startsWith('data:') || f.photo.startsWith('http')) 
                                    ? f.photo 
                                    : config.storagePath + f.photo;
                            } else {
                                this.previews.findings[i] = '';
                            }
                        });
                    }
                },
                get validationErrors() {
                    let errors = [];
                    if (!this.previews.before) errors.push('Foto de Situación Inicial');
                    if (!this.previews.after) errors.push('Foto de Trabajo Finalizado');
                    if (!this.building) errors.push('Edificio / Bloque');
                    if (!this.floor) errors.push('Nivel / Piso');
                    if (!this.specific_area) errors.push('Área Específica');
                    
                    // Check evaluations
                    const missingEvals = Object.values(this.evaluations).filter(v => v === '').length;
                    if (missingEvals > 0) errors.push(missingEvals + ' Puntos del Checklist');
                    
                    return errors;
                },
                get canFinalize() {
                    return this.validationErrors.length === 0;
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
                async doSubmit(actionType) {
                    if(this.isSubmitting) return;
                    
                    if(actionType === 'save_draft' && !confirm('¿Deseas guardar los cambios actuales como borrador?')) return;
                    if(actionType === 'submit' && !confirm('¿Estás seguro de FINALIZAR este reporte? Una vez enviado no podrá ser editado.')) return;

                    if(actionType === 'reject') {
                        const comment = document.querySelector('textarea[name="review_comment"]');
                        if(!comment || !comment.value.trim()) {
                            alert('Es obligatorio indicar el motivo del rechazo.');
                            return;
                        }
                    }

                    this.isSubmitting = true;
                    this.$refs.actionField.value = actionType;

                    // Improved connectivity check: actually try the network if possible
                    let isActuallyOnline = navigator.onLine;
                    
                    if (isActuallyOnline) {
                        this.$refs.form.dataset.submitting = 'true';
                        try {
                            if (typeof this.$refs.form.requestSubmit === 'function') {
                                this.$refs.form.requestSubmit();
                                return; // Browser takes over
                            }
                        } catch (err) {
                            console.warn('requestSubmit failed, falling back', err);
                        }
                        this.$refs.form.submit();
                        return;
                    }

                    // OFFLINE FALLBACK
                    try {
                        const formData = new FormData(this.$refs.form);
                        const taskPayload = {
                            id: Date.now(),
                            original_task_id: '{{ $task->id }}',
                            url: this.$refs.form.action,
                            fields: {},
                            blobs: []
                        };

                        for (let [key, value] of formData.entries()) {
                            if (value instanceof File && value.size > 0) {
                                const dataUrl = await new Promise(resolve => {
                                    const reader = new FileReader();
                                    reader.onload = e => resolve(e.target.result);
                                    reader.readAsDataURL(value);
                                });
                                taskPayload.blobs.push({ fieldName: key, fileName: value.name, data: dataUrl });
                            } else if (!(value instanceof File)) {
                                if (taskPayload.fields[key]) {
                                    if (!Array.isArray(taskPayload.fields[key])) taskPayload.fields[key] = [taskPayload.fields[key]];
                                    taskPayload.fields[key].push(value);
                                } else {
                                    taskPayload.fields[key] = value;
                                }
                            }
                        }

                        await window.offlineDB.saveTask(taskPayload);
                        alert('⚠️ GUARDADO LOCAL: Estás offline. Los cambios se guardaron en tu celular y se sincronizarán al recuperar señal.');
                        window.location.href = "{{ route('technician.dashboard') }}";
                    } catch (e) {
                        console.error('Offline save error:', e);
                        alert('Error al guardar localmente. Por favor, busca señal para guardar directamente.');
                        this.isSubmitting = false;
                    }
                }
            }));

            // Abandonment Warning logic
            window.addEventListener('beforeunload', (event) => {
                const form = document.getElementById('tech-form');
                if (form && !form.dataset.submitting) {
                    // Check if it's a new task with no progress but potentially some typed data
                    // For simplicity, we can just warn if the user didn't click one of our buttons
                    // However, standard browser warning is better.
                }
            });
        };

        if (window.Alpine) {
            initTechnicianForm();
        } else {
            document.addEventListener('alpine:init', initTechnicianForm);
        }
    </script>
    @endpush
</x-technician-layout>
