<x-technician-layout>
    <div class="px-5 pt-6 max-w-7xl mx-auto md:py-10 md:px-8 lg:px-12">
        <!-- Saludo y Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div>
                <h2 class="text-3xl font-black text-white leading-tight">Control de<br><span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Tareas Asignadas</span></h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1 px-1 hidden md:block">Gestión de hoja de ruta y mantenimiento técnico</p>
            </div>
            <div class="flex gap-4">
                <div class="bg-white/5 border border-white/10 px-6 py-2 rounded-2xl text-center shadow-xl backdrop-blur-md">
                    <span class="block text-[10px] font-black text-gray-500 uppercase tracking-tighter mb-1">Total Pendientes</span>
                    <span class="text-2xl font-black text-white leading-none">{{ count($tasks) }}</span>
                </div>
            </div>
        </div>
        
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-bold p-4 rounded-2xl mb-8 text-xs flex items-center gap-3 shadow-lg backdrop-blur-md max-w-2xl mx-auto md:mx-0" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                <div class="w-8 h-8 bg-emerald-500/20 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                {{ session('success') }}
            </div>
        @endif

        <!-- Listado Vertical de Tareas Pendientes -->
        <div class="mb-10">
            <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-5 px-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-tecsisa-yellow animate-pulse"></span> Hoja de Ruta Activa
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                @foreach($tasks as $task)
                    @php
                        $route = $task->status !== 'completed' ? route('tasks.edit', $task->id) : route('technician.task.show', $task->id);
                        $isDraft = $task->status === 'draft';
                    @endphp
                    
                    <a href="{{ $route }}" class="block group">
                        <div class="bg-gradient-to-r from-[#12161f] to-[#0a0d14] border border-white/5 rounded-[2rem] p-5 flex items-center gap-4 hover:border-tecsisa-yellow/30 transition-all active:scale-[0.98] relative overflow-hidden shadow-xl">
                            
                            <!-- Icono de Tipo de Tarea -->
                            <div class="w-14 h-14 shrink-0 rounded-2xl flex items-center justify-center relative z-10 
                                {{ $task->task_type === 'replacement' ? 'bg-red-500/10 text-red-500' : ($task->task_type === 'installation' ? 'bg-cyan-500/10 text-cyan-500' : 'bg-tecsisa-yellow/10 text-tecsisa-yellow') }}">
                                @if($task->task_type === 'replacement')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                @elseif($task->task_type === 'installation')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                @else
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                @endif
                            </div>

                            <!-- Info Central -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[9px] font-black uppercase tracking-tighter px-2 py-0.5 rounded-md
                                        {{ $task->priority === 'critical' ? 'bg-red-500 text-white shadow-[0_0_10px_rgba(239,68,68,0.4)]' : ($task->priority === 'high' ? 'bg-orange-500/20 text-orange-400' : 'bg-white/5 text-gray-500') }}">
                                        {{ $task->priority }}
                                    </span>
                                    @if($isDraft)
                                        <span class="text-[9px] font-black uppercase tracking-tighter px-2 py-0.5 rounded-md bg-tecsisa-yellow/20 text-tecsisa-yellow">BORRADOR</span>
                                    @endif
                                </div>
                                <h4 class="text-sm font-black text-white truncate group-hover:text-tecsisa-yellow transition-colors">{{ $task->title }}</h4>
                                <p class="text-[11px] text-gray-500 font-bold uppercase tracking-tight flex items-center gap-1.5 mt-0.5">
                                    <svg class="w-3 h-3 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    <span class="text-white">{{ $task->location_snapshot ?? ($task->equipment->location->name ?? 'Ubicación Desconocida') }}</span>
                                </p>
                            </div>

                            <!-- Indicador de Acción -->
                            <div class="shrink-0">
                                <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-gray-600 group-hover:bg-tecsisa-yellow group-hover:text-black transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </a>

                @endforeach
            </div>
        </div>

        <!-- Sección de Finalizados Recientemente -->
        @if(count($completedTasks) > 0)
            <div class="mt-16">
                <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-6 flex items-center gap-3">
                    <span class="p-1 rounded bg-emerald-500/10 text-emerald-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></span>
                    Recién Completadas
                    <span class="text-emerald-500/50 ml-auto">{{ count($completedTasks) }}</span>
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($completedTasks as $ct)
                        <div class="flex items-center gap-4 bg-black/40 p-4 rounded-2xl border border-white/5 opacity-80 group hover:opacity-100 transition">
                            <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center shrink-0 border border-green-500/20">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="text-xs font-bold text-gray-200 truncate">{{ $ct->title }}</h5>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[9px] font-bold text-gray-600 uppercase tracking-tighter">{{ optional($ct->completed_at)->diffForHumans() }}</span>
                                    <span class="text-[8px] bg-white/5 px-1.5 rounded text-gray-500 font-mono">TK-{{ str_pad($ct->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(count($tasks) == 0 && count($completedTasks) == 0)
            <div class="flex flex-col items-center justify-center py-24 px-8 bg-[#12161f]/30 rounded-[3rem] border border-dashed border-white/5 text-center mt-10">
                <div class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-500/20">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="font-black text-white text-xl uppercase tracking-widest">Día Productivo</h3>
                <p class="text-sm text-gray-500 mt-2 leading-relaxed max-w-sm">No tienes tareas pendientes asignadas. Todo el equipo está funcionando según los parámetros.</p>
                <button @click="showScannerMenu = true" class="mt-8 bg-tecsisa-yellow text-black font-black px-8 py-4 rounded-2xl text-[10px] uppercase tracking-widest shadow-xl shadow-yellow-400/20 active:scale-95 transition">Escanear Nuevo Equipo</button>
            </div>
        @endif
    </div>
</x-technician-layout>
