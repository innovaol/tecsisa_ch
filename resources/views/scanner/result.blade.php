<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-wide leading-tight flex items-center gap-3">
            <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ __('Identificación Confirmada') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-tecsisa-card backdrop-blur-xl rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.5)] border border-green-500/30 overflow-hidden relative">
                
                <div class="absolute top-0 right-0 p-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400 border border-green-500/50 shadow-[0_0_15px_rgba(34,197,94,0.3)]">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> CONEXIÓN A BDR ESTABLECIDA
                    </span>
                </div>

                <div class="p-8 border-b border-white/5 bg-gradient-to-br from-green-500/10 to-transparent">
                    <div class="flex items-start gap-6">
                        <div class="p-4 bg-black/40 rounded-2xl border border-white/10 shadow-inner">
                            <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                        </div>
                        <div>
                            <p class="text-tecsisa-yellow font-mono text-xl font-bold tracking-widest shadow-sm">{{ $equipment->internal_id }}</p>
                            <h3 class="text-3xl font-extrabold text-white mt-1 mb-2">{{ $equipment->name }}</h3>
                            <div class="flex flex-wrap gap-3 mt-3">
                                <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-sm text-gray-300 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    Sistema: {{ $equipment->system->name }}
                                </span>
                                <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-sm text-gray-300 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $equipment->location ? $equipment->location->name : 'No Asignada' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 p-8 gap-8 bg-black/20">
                    <div>
                        <h4 class="text-lg font-semibold text-white mb-4 border-b border-white/10 pb-2">Datos Técnicos Base</h4>
                        <div class="space-y-4">
                            @if(is_array($equipment->specs) && count($equipment->specs) > 0)
                                @foreach($equipment->specs as $key => $value)
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 font-mono uppercase tracking-wider">{{ str_replace('_', ' ', $key) }}</span>
                                        <span class="text-sm text-gray-200 mt-1 font-medium bg-white/5 px-3 py-2 rounded border border-white/5">{{ $value }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-500 text-sm italic">Sin datos técnicos extra registrados.</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col h-full">
                        <h4 class="text-lg font-semibold text-white mb-4 border-b border-white/10 pb-2">Acción Requerida</h4>
                        
                        <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-5 mb-6 flex-grow shadow-inner">
                            <p class="text-sm text-blue-300 mb-3 font-medium">
                                Has localizado correctamente el equipo. ¿Qué intervención deseas registrar para este activo?
                            </p>
                            <ul class="text-sm text-gray-400 space-y-2 list-disc pl-4">
                                <li>Mantenimiento Preventivo (Checklist)</li>
                                <li>Reporte de Avería (Correctivo)</li>
                                <li>Auditoría de Estado</li>
                            </ul>
                        </div>

                        <form action="{{ route('tasks.store') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                            <input type="hidden" name="title" value="Intervención en {{ $equipment->internal_id }}">
                            <input type="hidden" name="priority" value="medium">
                            <button type="submit" class="w-full py-4 text-tecsisa-dark font-bold text-lg rounded-xl transition duration-300 bg-tecsisa-yellow hover:bg-yellow-400 hover:-translate-y-1 shadow-[0_5px_20px_rgba(255,209,0,0.4)] flex justify-center items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Iniciar Tarea / Formulario Técnico
                            </button>
                        </form>
                    </div>
                </div>

            </div>
            
            <div class="mt-6 text-center">
                 <a href="{{ route('scanner.index') }}" class="text-gray-400 hover:text-white transition text-sm">
                     &larr; Ceder identificación y buscar otro
                 </a>
            </div>
        </div>
    </div>
</x-app-layout>
