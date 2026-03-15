<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Control de Producción / Tabla Maestra') }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('reports.weekly.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 transition shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Reportes Ejecutivos
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filtros Rápidos -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-3xl p-6 mb-8 border border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('reports.production') }}" class="grid grid-cols-2 md:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase mb-2 tracking-widest">Sistema</label>
                        <select name="system_id" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm">
                            <option value="">Todos los Sistemas</option>
                            @foreach($systems as $s)
                                <option value="{{ $s->id }}" {{ request('system_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase mb-2 tracking-widest">Desde</label>
                        <input type="text" name="start_date" id="start_date" value="{{ request('start_date') }}" placeholder="dd/mm/aaaa" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm h-[38px] px-3">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase mb-2 tracking-widest">Hasta</label>
                        <input type="text" name="end_date" id="end_date" value="{{ request('end_date') }}" placeholder="dd/mm/aaaa" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm h-[38px] px-3">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase mb-2 tracking-widest">Edificio</label>
                        <input type="text" name="building" value="{{ request('building') }}" placeholder="Ej: G" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm h-[38px] px-3">
                    </div>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label class="block text-[10px] font-black text-gray-500 uppercase mb-2 tracking-widest">Técnico</label>
                            <select name="technician_id" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm">
                                <option value="">Todos</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ request('technician_id') == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-1">
                            <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-black w-10 h-[38px] rounded-xl transition shadow-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                            <a href="{{ route('reports.production') }}" class="w-10 h-[38px] bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-white rounded-xl flex items-center justify-center transition hover:bg-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        </div>
                    </div>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const fpConfig = {
                            dateFormat: "Y-m-d", // Backend
                            altInput: true,
                            altFormat: "d/m/Y", // Visual
                            locale: "es",
                            allowInput: true,
                        };
                        flatpickr("#start_date", fpConfig);
                        flatpickr("#end_date", fpConfig);
                    });
                </script>
            </div>

            <!-- Tabla de Producción Digital -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl sm:rounded-[2rem] border border-gray-100 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Fecha</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Edificio</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Sistema</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Rack</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">TAG / ID</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">N. Cable</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">N. Jack</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">N. FP</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                            @forelse($tasks as $task)
                                <tr class="hover:bg-gray-50 dark:hover:bg-black/20 transition-colors">
                                    <td class="px-6 py-4 text-xs font-bold dark:text-gray-300">{{ $task->completed_at ? $task->completed_at->format('d/m/Y') : '-' }}</td>
                                    <td class="px-6 py-4 text-xs font-bold text-gray-700 dark:text-gray-400">{{ $task->form_data['building'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-0.5 rounded-md text-[9px] font-black uppercase text-white bg-blue-500/80 shadow-sm shadow-blue-500/20">
                                            {{ $task->system->name ?? 'GENERAL' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold text-gray-700 dark:text-gray-400">
                                        {{ $task->equipment->rack->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-mono font-bold text-yellow-600 dark:text-yellow-400">{{ $task->equipment->internal_id }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-black {{ $task->has_new_cable ? 'text-emerald-500' : 'text-gray-300' }}">{{ $task->has_new_cable ? 'SI' : 'NO' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-black {{ $task->has_new_jack ? 'text-emerald-500' : 'text-gray-300' }}">{{ $task->has_new_jack ? 'SI' : 'NO' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-black {{ $task->has_new_faceplate ? 'text-emerald-500' : 'text-gray-300' }}">{{ $task->has_new_faceplate ? 'SI' : 'NO' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($task->is_certified)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-black bg-emerald-100 text-emerald-700 uppercase tracking-tighter shadow-sm border border-emerald-200">Certif.</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-black bg-gray-100 text-gray-600 uppercase tracking-tighter border border-gray-200">Pend.</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('tasks.edit', $task) }}" class="p-2 bg-gray-100 dark:bg-gray-700 hover:bg-yellow-400 dark:hover:bg-yellow-400 text-gray-600 dark:text-white hover:text-black rounded-xl transition shadow-inner">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <a href="{{ route('tasks.pdf', $task) }}" target="_blank" class="p-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-inner">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-20 text-center text-gray-500 font-bold uppercase tracking-widest text-sm opacity-30">
                                        No hay datos de producción registrados para este periodo
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
                    {{ $tasks->links() }}
                </div>
            </div>

            <!-- Resumen de Totales Estimados -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-10">
                <div class="bg-blue-600 p-6 rounded-[2rem] shadow-2xl">
                    <p class="text-[10px] font-black text-blue-100 uppercase tracking-widest mb-1">Mts Cable Estimado</p>
                    <p class="text-3xl font-black text-white">4,280 <span class="text-xs">m</span></p>
                </div>
                <div class="bg-gray-800 dark:bg-gray-900 p-6 rounded-[2rem] shadow-2xl border border-white/5">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Puntos Finalizados</p>
                    <p class="text-3xl font-black text-white">{{ $tasks->total() }}</p>
                </div>
                <div class="bg-emerald-600 p-6 rounded-[2rem] shadow-2xl">
                    <p class="text-[10px] font-black text-emerald-100 uppercase tracking-widest mb-1">Puntos Certificados</p>
                    <p class="text-3xl font-black text-white">{{ $tasks->where('is_certified', true)->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
