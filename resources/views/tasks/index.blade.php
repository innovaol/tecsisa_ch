<x-app-layout>
    <div class="py-6 md:py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" x-data="{ showCreateModal: false }">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white leading-tight">Control de <span class="text-tecsisa-yellow uppercase tracking-widest text-xs font-black">Tareas</span></h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1 px-1">Supervisión y asignación global de trabajos</p>
            </div>
            <div class="flex gap-3">
                @if(Auth::user()->hasRole('Administrador'))
                <button @click="showCreateModal = true" class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black px-4 md:px-6 py-2 md:py-3 rounded-xl md:rounded-2xl text-[10px] md:text-xs uppercase tracking-widest transition shadow-xl shadow-yellow-400/20 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nueva Tarea
                </button>
                @endif
                <a href="{{ route('catalog.index') }}?tab=equipment" class="bg-white/5 hover:bg-white/10 text-white font-bold px-4 md:px-6 py-2 md:py-3 rounded-xl md:rounded-2xl text-[10px] md:text-xs uppercase tracking-widest transition border border-white/10 flex items-center gap-2 hidden md:flex">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Ver Catálogo
                </a>
            </div>
        </div>

        <!-- Tasks Table -->
        <div class="bg-[#0f1217]/50 backdrop-blur-xl border border-white/5 rounded-3xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white/[0.02] border-b border-white/5">
                            <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Tarea</th>
                            <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Activo / Ubicación</th>
                            <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Responsable</th>
                            <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Estado</th>
                            <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($tasks as $task)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-3.5 text-xs">
                                <div class="flex flex-col">
                                    <span class="font-black text-white group-hover:text-tecsisa-yellow transition-colors leading-tight">{{ $task->title }}</span>
                                    <span class="text-[9px] text-gray-500 font-bold uppercase tracking-tighter mt-0.5">{{ $task->task_type }} - {{ $task->priority }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-xs">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-300 leading-tight">{{ $task->equipment->name ?? 'N/A' }}</span>
                                    <span class="text-[9px] text-gray-500 uppercase font-black mt-0.5">{{ $task->equipment->location->name ?? 'Sin ubicación' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-xs">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-6 h-6 rounded-lg bg-blue-500/10 flex items-center justify-center border border-blue-500/20 text-blue-400 text-[9px] font-black uppercase">
                                        {{ substr($task->assignee->name ?? '?', 0, 1) }}
                                    </div>
                                    <span class="font-bold text-gray-400">{{ $task->assignee->name ?? 'Sin asignar' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-xs">
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider 
                                    {{ $task->status == 'completed' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : '' }}
                                    {{ $task->status == 'pending' ? 'bg-tecsisa-yellow/20 text-tecsisa-yellow border border-tecsisa-yellow/30' : '' }}
                                    {{ $task->status == 'draft' ? 'bg-gray-500/20 text-gray-400 border border-gray-500/30' : '' }}">
                                    {{ $task->status }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('tasks.edit', $task) }}" class="p-1.5 bg-white/5 rounded-lg border border-white/10 text-gray-400 hover:text-white transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('¿Eliminar esta tarea definitivamente?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-500/10 rounded-lg border border-red-500/10 text-red-400 hover:bg-red-500/20 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center opacity-40">
                                    <svg class="w-8 h-8 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    <p class="text-[10px] font-black uppercase tracking-widest">Sin tareas registradas</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(Auth::user()->hasRole('Administrador'))
        <!-- Create Task Modal -->
    <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showCreateModal = false" x-transition.opacity></div>
        <div class="bg-[#12161f] border border-white/10 rounded-2xl md:rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl relative z-10 flex flex-col max-h-full"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95">
            
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/5 shrink-0">
                <h2 class="text-lg font-black text-white tracking-widest uppercase">Crear Nueva <span class="text-tecsisa-yellow">Tarea</span></h2>
                <button @click="showCreateModal = false" class="text-gray-500 hover:text-white transition bg-black/50 w-8 h-8 rounded-full flex items-center justify-center border border-white/5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-5">
                        <!-- Título -->
                        <div>
                            <label class="block text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Título de la Tarea</label>
                            <input type="text" name="title" required
                                   class="w-full bg-black/40 border-white/10 rounded-xl text-white text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-11 px-4 placeholder:text-gray-600 font-bold"
                                   placeholder="Ej: Mantenimiento Preventivo Switch...">
                        </div>

                        <!-- Tipo y Prioridad -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Tipo</label>
                                <select name="task_type" required class="w-full bg-black/40 border-white/10 rounded-xl text-white text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-11 px-4 font-bold">
                                    <option value="maintenance">Mantenimiento</option>
                                    <option value="replacement">Reemplazo</option>
                                    <option value="installation">Instalación / Deploy</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Prioridad</label>
                                <select name="priority" required class="w-full bg-black/40 border-white/10 rounded-xl text-white text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-11 px-4 font-bold">
                                    <option value="low">Baja</option>
                                    <option value="medium" selected>Media</option>
                                    <option value="high">Alta</option>
                                    <option value="critical">Crítica</option>
                                </select>
                            </div>
                        </div>

                        <!-- Activo -->
                        <div>
                            <label class="block text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Equipo / Activo</label>
                            <select name="equipment_id" required class="w-full bg-black/40 border-white/10 rounded-xl text-white text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-11 px-4 font-bold">
                                <option value="">-- Seleccionar Equipo --</option>
                                @foreach($equipments as $eq)
                                    <option value="{{ $eq->id }}">{{ $eq->internal_id }} - {{ $eq->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Responsable -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Responsable</label>
                                <select name="assigned_to" class="w-full bg-black/40 border-white/10 rounded-xl text-white text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-11 px-4 font-bold">
                                    <option value="">A Mí ({{ Auth::user()->name }})</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Estado</label>
                                <select name="status" required class="w-full bg-black/40 border-white/10 rounded-xl text-white text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-11 px-4 font-bold">
                                    <option value="draft">Borrador</option>
                                    <option value="pending" selected>Pendiente</option>
                                </select>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label class="block text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Notas / Descripción</label>
                            <textarea name="description" rows="3" class="w-full bg-black/40 border-white/10 rounded-xl text-white text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition p-4 placeholder:text-gray-600 font-bold" placeholder="Detalles de la tarea..."></textarea>
                        </div>

                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-white/5">
                        <button type="button" @click="showCreateModal = false" class="px-6 py-2.5 rounded-xl text-gray-400 hover:text-white transition font-bold uppercase text-[10px] tracking-widest">
                            Cancelar
                        </button>

                        <button type="submit" class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black px-8 py-2.5 rounded-xl transition shadow-xl shadow-yellow-400/20 uppercase text-[10px] tracking-widest">
                            Crear Tarea
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    </div>
</x-app-layout>
