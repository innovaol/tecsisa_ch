<x-technician-layout>
    <div class="px-5 py-6">
        <!-- Saludo -->
        <h2 class="text-3xl font-black text-white leading-tight mb-2">Mis Tareas<br><span class="text-gray-500 font-normal">Pendientes</span></h2>
        
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/20 text-green-400 font-bold p-3 rounded-xl mb-6 text-sm flex items-center gap-2" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Carrusel Horizontal de Tareas Pendientes (Native swipe behavior) -->
        <div class="mt-8 mb-10 -mx-5 px-5 flex overflow-x-auto no-scrollbar snap-x snap-mandatory gap-4 pb-4">
            
            @forelse($tasks as $task)
                @php
                    $route = $task->status === 'draft' ? route('tasks.edit', $task->id) : route('technician.task.show', $task->id);
                @endphp
                <div class="snap-center shrink-0 w-[85vw] max-w-sm rounded-[2rem] bg-gradient-to-b from-[#1a1f26] to-[#0f1217] border border-white/10 p-6 flex flex-col justify-between shadow-2xl relative overflow-hidden" onclick="window.location='{{ $route }}'">
                    
                    @if($task->priority === 'critical')
                        <!-- Efecto glow para críticas -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/10 blur-[50px] pointer-events-none rounded-full"></div>
                    @endif

                    <!-- Header Tarjeta -->
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex flex-col gap-2">
                            <div class="bg-black/50 border border-white/5 backdrop-blur-md px-3 py-1.5 rounded-full text-[10px] font-black tracking-widest uppercase flex items-center gap-2 max-w-fit">
                                @if($task->priority === 'critical')
                                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span><span class="text-red-400">Crítica</span>
                                @elseif($task->priority === 'high')
                                    <span class="w-2 h-2 rounded-full bg-orange-500"></span><span class="text-orange-400">Alta</span>
                                @elseif($task->priority === 'medium')
                                    <span class="w-2 h-2 rounded-full bg-yellow-400"></span><span class="text-yellow-400">Media</span>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-blue-400"></span><span class="text-blue-400">Baja</span>
                                @endif
                            </div>
                            
                            @if($task->status === 'draft')
                                <div class="bg-tecsisa-yellow/10 border border-tecsisa-yellow/20 px-3 py-1 rounded-full text-[9px] font-black tracking-tighter uppercase text-tecsisa-yellow max-w-fit">
                                    Borrador en Sitio
                                </div>
                            @endif
                        </div>
                        
                        <div class="text-[10px] text-gray-500 font-mono font-bold">#TK-{{ str_pad($task->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </div>

                    <!-- Contenido Central -->
                    <div class="mb-4">
                        <span class="text-[10px] uppercase font-black text-gray-600 tracking-widest mb-1 block">{{ $task->task_type === 'replacement' ? 'Reemplazo' : ($task->task_type === 'installation' ? 'Instalación' : 'Mantenimiento') }}</span>
                        <h3 class="text-xl font-bold text-white mb-2 leading-tight drop-shadow-md">{{ $task->title }}</h3>
                        <p class="text-gray-400 text-xs tracking-wide uppercase font-bold flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $task->equipment->location->name ?? 'Múltiple' }}
                        </p>
                    </div>

                    <!-- Footer / Botón Acción -->
                    <div class="flex items-center justify-between border-t border-white/5 pt-4">
                        <div class="flex flex-col">
                            <span class="text-gray-500 text-[10px] uppercase font-bold tracking-widest">Activo Asignado</span>
                            <span class="font-mono text-tecsisa-yellow text-sm font-bold">{{ $task->equipment->internal_id ?? 'N/A' }}</span>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white cursor-pointer hover:bg-white/10 transition-colors">
                            @if($task->status === 'draft')
                                <svg class="w-5 h-5 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="w-full flex flex-col items-center justify-center py-12 px-6 bg-black/40 rounded-3xl border border-dashed border-white/10 text-center">
                    <div class="w-16 h-16 bg-green-500/10 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="font-bold text-white text-lg">Excelente Trabajo</h3>
                    <p class="text-sm text-gray-500 mt-1">No tienes trabajos preventivos o correctivos pendientes en este momento.</p>
                </div>
            @endforelse
        </div>

        <!-- Lista Vertical: Actividades Cerradas Recientemente -->
        <h4 class="text-xs font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Finalizadas Recientemente</h4>
        <div class="space-y-3">
            @forelse($completedTasks as $ct)
                <div class="bg-black/40 border border-white/5 rounded-2xl p-4 flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-800/50 rounded-full border border-gray-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <h5 class="text-sm font-bold text-gray-300 truncate">{{ $ct->title }}</h5>
                        <p class="text-[10px] uppercase font-bold text-gray-600 mt-1">{{ optional($ct->completed_at)->diffForHumans() }}</p>
                    </div>
                    <div class="text-[10px] font-mono font-bold text-gray-500">TK-{{ str_pad($ct->id, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
            @empty
                <div class="text-center text-xs text-gray-600 italic py-4">No hay tareas históricas recientes.</div>
            @endforelse
        </div>
    </div>
</x-technician-layout>
