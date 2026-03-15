<x-app-layout>
    <div class="py-6 md:py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8" x-data="{ 
        showCreateModal: new URLSearchParams(window.location.search).has('showModal'),
        newTask: {
            title: '',
            equipment: ''
        },
        get canCreate() {
            return this.newTask.title.trim().length > 0 && this.newTask.equipment !== '';
        }
    }">
        <!-- Header: Tarjeta Propia -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 mb-6 transition-all duration-500 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-tecsisa-yellow/5 rounded-full blur-2xl"></div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-black transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                        Gestión de <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Operaciones</span>
                    </h2>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-2 px-1">
                        {{ Auth::user()->hasRole('Administrador') ? 'Asignación, seguimiento y trazabilidad técnica' : 'Hoja de ruta y reportes asignados' }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <button @click="showCreateModal = true" class="bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black px-8 py-3.5 rounded-2xl text-xs uppercase tracking-widest transition shadow-xl active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        {{ Auth::user()->hasRole('Administrador') ? 'Asignar Tarea' : 'Nueva Tarea' }}
                    </button>
                </div>
            </div>
        </div>

        @if(!empty($stats))
        <!-- Stats Summary -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-theme-card border border-theme p-6 rounded-[2rem] transition-all duration-500 shadow-lg">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2" :class="theme === 'light' ? 'text-slate-500' : 'text-gray-500'">Total Tareas</p>
                <p class="text-3xl font-black transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-theme-card border border-theme p-6 rounded-[2rem] transition-all duration-500 shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 w-16 h-16 bg-tecsisa-yellow/5 rounded-bl-full translate-x-4 -translate-y-4"></div>
                <p class="text-[10px] font-black text-tecsisa-yellow uppercase tracking-widest mb-2">Activa</p>
                <p class="text-3xl font-black text-tecsisa-yellow">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-theme-card border border-theme p-6 rounded-[2rem] transition-all duration-500 shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 w-16 h-16 bg-blue-500/5 rounded-bl-full translate-x-4 -translate-y-4"></div>
                <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2">Aprobación</p>
                <p class="text-3xl font-black text-blue-400">{{ $stats['in_review'] }}</p>
            </div>
            <div class="bg-theme-card border border-theme p-6 rounded-[2rem] transition-all duration-500 shadow-lg relative overflow-hidden col-span-2 lg:col-span-1">
                <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/5 rounded-bl-full translate-x-4 -translate-y-4"></div>
                <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-2">Completadas</p>
                <p class="text-3xl font-black text-emerald-400">{{ $stats['completed'] }}</p>
            </div>
        </div>
        @endif

        <!-- Tabs / Filters: Tarjeta Propia -->
        <div class="bg-theme-card border border-theme rounded-3xl p-2 mb-8 transition-all duration-500 shadow-lg flex items-center gap-2 overflow-x-auto no-scrollbar">
            <a href="{{ route('tasks.index') }}" 
               class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap" :class="!@js(request('status')) ? 'bg-tecsisa-yellow text-black' : 'text-gray-500 hover:text-slate-800 dark:hover:text-white'">Todos</a>
            <a href="{{ route('tasks.index', ['status' => 'pending']) }}" 
               class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap" :class="@js(request('status')) == 'pending' ? 'bg-tecsisa-yellow text-black' : 'text-gray-500 hover:text-slate-800 dark:hover:text-white'">Activa</a>
            <a href="{{ route('tasks.index', ['status' => 'in_review']) }}" 
               class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap" :class="@js(request('status')) == 'in_review' ? 'bg-tecsisa-yellow text-black' : 'text-gray-500 hover:text-slate-800 dark:hover:text-white'">Aprobación</a>
            <a href="{{ route('tasks.index', ['status' => 'completed']) }}" 
               class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap" :class="@js(request('status')) == 'completed' ? 'bg-tecsisa-yellow text-black' : 'text-gray-500 hover:text-slate-800 dark:hover:text-white'">Completadas</a>
        </div>

        <!-- Tasks Table (Desktop): Tarjeta Propia -->
        <div class="hidden md:block bg-theme-card border border-theme rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-black/10 border-b border-theme text-[10px] font-black uppercase text-gray-500 tracking-[0.2em] transition-colors duration-500" :class="theme === 'light' ? 'bg-slate-50' : ''">
                            <th class="px-8 py-5">Tarea</th>
                            <th class="px-6 py-5">Activo / Ubicación</th>
                            <th class="px-6 py-5">Tiempos</th>
                            <th class="px-6 py-5">Responsable</th>
                            <th class="px-6 py-5">Estado</th>
                            <th class="px-8 py-5 text-right font-black">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme transition-colors duration-500">
                        @forelse($tasks as $task)
                        <tr class="hover:bg-theme-table-row-hover transition-colors duration-500 border-b border-theme last:border-0 group">
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <span class="font-black text-sm uppercase transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $task->title }}</span>
                                    <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-1">{{ $task->task_type }} — PRIORIDAD {{ $task->priority }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="font-bold text-xs transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-700' : 'text-gray-300'">{{ $task->equipment->name ?? 'Activo eliminado' }}</span>
                                    <span class="text-[9px] text-gray-500 uppercase font-black tracking-widest mt-1">{{ $task->location_snapshot ?? ($task->equipment->location->name ?? 'Sin ubicación') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2 text-[9px] font-black uppercase tracking-tighter" style="color: var(--theme-text-muted)">
                                        <span class="text-blue-500/60 w-12">Inicio:</span>
                                        <span class="transition-colors duration-500" :class="theme === 'light' ? 'text-slate-600' : 'text-gray-400'">{{ $task->created_at->format('d/m H:i') }}</span>
                                    </div>
                                    @if($task->completed_at)
                                    <div class="flex items-center gap-2 text-[9px] font-black uppercase tracking-tighter">
                                        <span class="text-emerald-500/60 w-12">Fin:</span>
                                        <span class="text-emerald-500">{{ $task->completed_at->format('d/m H:i') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-blue-500/10 flex items-center justify-center border border-blue-500/20 text-blue-400 text-[10px] font-black uppercase shadow-lg transition-colors duration-500">
                                        {{ substr($task->assignee->name ?? '?', 0, 1) }}
                                    </div>
                                    <span class="font-black text-[10px] uppercase tracking-widest transition-colors duration-500" style="color: var(--theme-text-muted)">{{ $task->assignee->name ?? 'Sin asignar' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-[0.2em] border transition-all
                                    {{ $task->status == 'completed' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20 shadow-[0_0_10px_rgba(16,185,129,0.1)]' : '' }}
                                    {{ $task->status == 'pending' ? 'bg-tecsisa-yellow/10 text-tecsisa-yellow border-tecsisa-yellow/20 shadow-[0_0_10px_rgba(255,209,0,0.1)]' : '' }}
                                    {{ $task->status == 'in_review' ? 'bg-blue-500/10 text-blue-400 border-blue-500/20 shadow-[0_0_10px_rgba(59,130,246,0.1)]' : '' }}
                                    {{ $task->status == 'draft' ? 'bg-gray-500/10 text-gray-400 border-theme' : '' }}">
                                    @if($task->status === 'draft') Borrador
                                    @elseif($task->status === 'pending') Activa
                                    @elseif($task->status === 'in_review') Aprobación
                                    @elseif($task->status === 'completed') Finalizada
                                    @else {{ $task->status }} @endif
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($task->status == 'completed')
                                    <a href="{{ route('tasks.pdf', $task) }}" class="p-2.5 bg-emerald-500/5 rounded-xl border border-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all shadow-md active:scale-90" title="Descargar Reporte PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </a>
                                    @endif
                                    @php
                                        $taskIsReadOnly = (in_array($task->status, ['completed', 'verified', 'in_review']) && !Auth::user()->hasRole('Administrador')) || in_array($task->status, ['completed', 'verified']);
                                    @endphp
                                     <a href="{{ route('tasks.edit', $task) }}" class="p-2.5 bg-theme/10 border border-theme rounded-xl text-theme hover:text-tecsisa-yellow transition-all shadow-md active:scale-90" title="{{ $taskIsReadOnly ? 'Ver Reporte' : 'Gestionar Tarea' }}">
                                        @if($taskIsReadOnly)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        @endif
                                    </a>
                                    @if(Auth::user()->hasRole('Administrador') || ($task->assigned_to === Auth::id() && $task->status === 'draft'))
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('¿Eliminar esta tarea definitivamente?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-red-500/5 rounded-xl border border-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-all shadow-md active:scale-90">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
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

        <!-- Tasks List (Mobile) -->
        <div class="md:hidden space-y-4">
            @forelse($tasks as $task)
            <div class="bg-theme-card border border-theme rounded-[2rem] p-5 shadow-2xl relative overflow-hidden group active:scale-[0.98] transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-1.5 flex items-center gap-1.5" :class="theme === 'light' ? 'text-slate-500' : 'text-gray-500'">
                            <span class="w-1 h-1 rounded-full bg-tecsisa-yellow"></span>
                            @if($task->task_type === 'maintenance') MANTENIMIENTO
                            @elseif($task->task_type === 'replacement') REEMPLAZO
                            @elseif($task->task_type === 'installation') INSTALACIÓN
                            @else {{ $task->task_type }} @endif
                             - 
                            @if($task->priority === 'low') BAJA
                            @elseif($task->priority === 'medium') MEDIA
                            @elseif($task->priority === 'high') ALTA
                            @elseif($task->priority === 'critical') CRÍTICA
                            @else {{ $task->priority }} @endif
                        </span>
                        <h3 class="text-sm font-black transition-colors" :class="theme === 'light' ? 'text-slate-800' : 'text-white group-hover:text-tecsisa-yellow'">{{ $task->title }}</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest border transition-all
                        {{ $task->status == 'completed' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : '' }}
                        {{ $task->status == 'pending' ? 'bg-tecsisa-yellow/10 text-tecsisa-yellow border-tecsisa-yellow/20' : '' }}
                        {{ $task->status == 'in_review' ? 'bg-blue-500/10 text-blue-400 border-blue-500/20' : '' }}
                        {{ $task->status == 'draft' ? 'bg-gray-500/10 text-gray-400 border-theme' : '' }}">
                        @if($task->status === 'draft') Borrador
                        @elseif($task->status === 'pending') Activa
                        @elseif($task->status === 'in_review') Aprobación
                        @elseif($task->status === 'completed') Finalizada
                        @else {{ $task->status }} @endif
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-5 p-4 rounded-2xl bg-theme/5 border border-theme shadow-inner">
                    <div>
                        <p class="text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Activo</p>
                        <p class="text-[10px] font-bold leading-tight" :class="theme === 'light' ? 'text-slate-700' : 'text-gray-200'">{{ $task->equipment->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Ubicación</p>
                        <p class="text-[10px] font-bold truncate leading-tight" :class="theme === 'light' ? 'text-slate-700' : 'text-gray-200'">{{ $task->location_snapshot ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-500/10 flex items-center justify-center border border-blue-500/10 text-blue-400 text-[10px] font-black uppercase shadow-lg">
                            {{ substr($task->assignee->name ?? '?', 0, 1) }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black leading-none" :class="theme === 'light' ? 'text-slate-700' : 'text-gray-300'">{{ $task->assignee->name ?? 'Sin asignar' }}</span>
                            <span class="text-[8px] text-gray-500 font-bold uppercase mt-1">{{ $task->created_at->format('d/m H:i') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        @if($task->status == 'completed')
                        <a href="{{ route('tasks.pdf', $task) }}" class="w-10 h-10 flex items-center justify-center bg-emerald-500/10 rounded-xl border border-emerald-500/10 text-emerald-400 shadow-lg active:scale-90 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </a>
                        @endif
                                    @php
                                        $taskIsReadOnly = (in_array($task->status, ['completed', 'verified', 'in_review']) && !Auth::user()->hasRole('Administrador')) || in_array($task->status, ['completed', 'verified']);
                                    @endphp
                                     <a href="{{ route('tasks.edit', $task) }}" class="w-10 h-10 flex items-center justify-center bg-theme/5 rounded-xl border border-theme text-gray-400 shadow-lg active:scale-90 transition" :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'" title="{{ $taskIsReadOnly ? 'Ver Reporte' : 'Gestionar Tarea' }}">
                                        @if($taskIsReadOnly)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        @endif
                                    </a>
                        @if(Auth::user()->hasRole('Administrador') || ($task->assigned_to === Auth::id() && $task->status === 'draft'))
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('¿Eliminar esta tarea definitivamente?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-500/10 rounded-xl border border-red-500/10 text-red-400 shadow-lg active:scale-90 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="py-12 flex flex-col items-center opacity-40">
                <svg class="w-10 h-10 text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <p class="text-xs font-black uppercase tracking-widest" :class="theme === 'light' ? 'text-slate-600' : 'text-white'">Sin tareas activas</p>
            </div>
            @endforelse
        </div>

        <!-- Create Task Modal -->
    <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showCreateModal = false" x-transition.opacity></div>
        <div class="bg-theme-card border border-theme rounded-2xl md:rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl relative z-10 flex flex-col max-h-full transition-all duration-500"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95">
            
            <div class="p-6 border-b border-theme flex justify-between items-center bg-theme/5 shrink-0">
                <h2 class="text-lg font-black tracking-widest uppercase transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Crear Nueva <span class="text-tecsisa-yellow">Tarea</span></h2>
                <button @click="showCreateModal = false" class="text-gray-500 transition bg-theme/5 w-8 h-8 rounded-full flex items-center justify-center border border-theme" :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-5">
                        <!-- Título -->
                        <div>
                            <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Título de la Tarea <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required x-model="newTask.title"
                                   class="w-full bg-theme/5 border border-theme rounded-xl text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-11 px-4 placeholder:text-gray-500 font-bold text-theme"
                                   placeholder="Ej: Mantenimiento Preventivo Switch...">
                        </div>

                        <!-- Tipo y Prioridad -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Tipo</label>
                                <select name="task_type" required class="w-full bg-theme/5 border border-theme rounded-xl text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-11 px-4 font-bold text-theme">
                                    <option value="maintenance">Mantenimiento</option>
                                    <option value="replacement">Reemplazo</option>
                                    <option value="installation">Instalación</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Prioridad</label>
                                <select name="priority" required class="w-full bg-theme/5 border border-theme rounded-xl text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-11 px-4 font-bold text-theme">
                                    <option value="low">Baja</option>
                                    <option value="medium" selected>Media</option>
                                    <option value="high">Alta</option>
                                    <option value="critical">Crítica</option>
                                </select>
                            </div>
                        </div>

                        <!-- Activo -->
                        <div>
                            <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Equipo / Activo <span class="text-red-500">*</span></label>
                            <select name="equipment_id" required x-model="newTask.equipment" class="w-full bg-theme/5 border border-theme rounded-xl text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-11 px-4 font-bold text-theme">
                                <option value="">-- Seleccionar Equipo --</option>
                                @foreach($equipments as $eq)
                                    <option value="{{ $eq->id }}">{{ $eq->internal_id }} - {{ $eq->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            @if(Auth::user()->hasRole('Administrador'))
                            <div>
                                <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Responsable</label>
                                <select name="assigned_to" class="w-full bg-theme/5 border border-theme rounded-xl text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-11 px-4 font-bold text-theme">
                                    <option value="">A Mí ({{ Auth::user()->name }})</option>
                                    @foreach($users as $user)
                                        @if($user->id !== Auth::id())
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            @else
                                <input type="hidden" name="assigned_to" value="{{ Auth::id() }}">
                            @endif

                            <div class="{{ !Auth::user()->hasRole('Administrador') ? 'col-span-2' : '' }}">
                                <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Estado</label>
                                <select name="status" required class="w-full bg-theme/5 border border-theme rounded-xl text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-11 px-4 font-bold text-theme">
                                    <option value="draft">Borrador</option>
                                    <option value="pending" selected>Activa</option>
                                </select>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-1.5 ml-1">Notas / Descripción</label>
                            <textarea name="description" rows="3" class="w-full bg-theme/5 border border-theme rounded-xl text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition p-4 placeholder:text-gray-500 font-bold text-theme" placeholder="Detalles de la tarea..."></textarea>
                        </div>

                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-theme">
                        <button type="button" @click="showCreateModal = false" class="px-6 py-2.5 rounded-xl text-gray-400 transition font-bold uppercase text-[10px] tracking-widest" :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'">
                            Cancelar
                        </button>

                        <div class="relative group">
                            <button type="submit" 
                                    :disabled="!canCreate"
                                    class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black px-8 py-2.5 rounded-xl transition shadow-xl shadow-yellow-400/20 uppercase text-[10px] tracking-widest disabled:opacity-50 disabled:cursor-not-allowed">
                                Crear Tarea
                            </button>

                            <!-- Tooltip de Requisitos -->
                            <div x-show="!canCreate" 
                                 class="absolute bottom-full right-0 mb-4 w-48 p-3 bg-red-600 text-white text-[9px] font-bold uppercase tracking-widest rounded-xl shadow-2xl opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-[100] border border-red-500">
                                 <div class="flex flex-col gap-1">
                                    <span class="text-white border-b border-red-400 pb-1 mb-1">Faltan Requisitos:</span>
                                    <template x-if="!newTask.title.trim()">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1 h-1 bg-white rounded-full"></span>
                                            <span>Título de Tarea</span>
                                        </div>
                                    </template>
                                    <template x-if="!newTask.equipment">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1 h-1 bg-white rounded-full"></span>
                                            <span>Seleccionar Equipo</span>
                                        </div>
                                    </template>
                                 </div>
                                 <div class="absolute top-full right-8 border-8 border-transparent border-t-red-600"></div>
                            </div>

                            <!-- Botón flotante de exclamación -->
                            <div x-show="!canCreate" 
                                 class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] font-black shadow-lg animate-bounce pointer-events-none">!</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
