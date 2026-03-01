<x-technician-layout>
    <div class="fixed top-0 inset-x-0 z-[60] bg-[#0a0d14]/95 backdrop-blur-3xl border-b border-white/5 pt-safe">
        <div class="px-4 py-4 flex items-center justify-between">
            <a href="{{ route('technician.scanner') }}" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white transition shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> Equipo Identificado
            </h1>
            <div class="w-10 h-10"></div>
        </div>
    </div>

    <div class="pt-24 pb-20 px-5 relative z-10">
        
        <!-- Info Principal -->
        <div class="bg-gradient-to-br from-[#12161f] to-[#0a0d14] rounded-3xl border border-white/10 p-6 shadow-2xl relative overflow-hidden mb-6">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-tecsisa-yellow/10 rounded-full blur-2xl"></div>

            <div class="w-16 h-16 bg-black border border-white/10 rounded-2xl flex items-center justify-center mb-4 shadow-inner">
                <svg class="w-8 h-8 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
            </div>
            
            <p class="text-tecsisa-yellow font-mono text-sm tracking-widest font-bold mb-1">{{ $equipment->internal_id }}</p>
            <h2 class="text-2xl font-bold text-white leading-tight mb-4">{{ $equipment->name }}</h2>
            
            <div class="flex flex-col gap-2">
                <span class="bg-black/50 border border-white/5 px-3 py-2 rounded-xl text-xs text-gray-300 flex items-center gap-2 font-bold">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Sistema: {{ $equipment->system->name ?? 'N/A' }}
                </span>
                <span class="bg-black/50 border border-white/5 px-3 py-2 rounded-xl text-xs text-gray-300 flex items-center gap-2 font-bold">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Ub: {{ $equipment->location ? $equipment->location->name : 'No Asignada' }}
                </span>
            </div>
        </div>

        <!-- Specs Extra -->
        <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">Ficha Técnica</h3>
        <div class="bg-[#12161f] border border-white/5 rounded-3xl p-5 mb-8">
            <div class="grid grid-cols-2 gap-4">
                @if(is_array($equipment->specs) && count($equipment->specs) > 0)
                    @foreach($equipment->specs as $key => $value)
                        <div class="flex flex-col justify-end border-b border-white/5 pb-2">
                            <span class="text-[9px] text-gray-500 font-bold uppercase tracking-wider">{{ str_replace('_', ' ', $key) }}</span>
                            <span class="text-xs font-mono font-bold text-gray-200 mt-0.5 break-all">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-2 py-4 text-center">
                        <span class="text-xs text-gray-600 font-bold italic">Sin parámetros extendidos</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tipos de Trabajo -->
        <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">¿Qué tipo de trabajo realizará?</h3>
        
        <div class="space-y-3">
            <!-- Botón Mantenimiento -->
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                <input type="hidden" name="title" value="Mantenimiento de Equipo">
                <input type="hidden" name="priority" value="medium">
                <input type="hidden" name="task_type" value="maintenance">
                <input type="hidden" name="description" value="Procedimiento de mantenimiento preventivo y/o correctivo.">

                <button type="submit" class="w-full bg-[#12161f] hover:bg-white/5 border border-transparent hover:border-tecsisa-yellow/30 text-white font-black p-4 rounded-3xl flex items-center justify-between transition group shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-tecsisa-yellow/10 rounded-2xl flex items-center justify-center text-tecsisa-yellow shrink-0 group-hover:bg-tecsisa-yellow group-hover:text-black transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-sm uppercase tracking-widest text-white mb-0.5">Mantenimiento</span>
                            <span class="block text-xs text-gray-500 font-normal">Revisión, limpieza o ajuste técnico</span>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-tecsisa-yellow transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </form>

            <!-- Botón Instalación -->
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                <input type="hidden" name="title" value="Instalación / Configuración">
                <input type="hidden" name="priority" value="medium">
                <input type="hidden" name="task_type" value="installation">
                <input type="hidden" name="description" value="Procedimiento de instalación y puesta a punto de un nuevo activo.">

                <button type="submit" class="w-full bg-[#12161f] hover:bg-white/5 border border-transparent hover:border-cyan-500/30 text-white font-black p-4 rounded-3xl flex items-center justify-between transition group shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-cyan-500/10 rounded-2xl flex items-center justify-center text-cyan-500 shrink-0 group-hover:bg-cyan-500 group-hover:text-black transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-sm uppercase tracking-widest text-white mb-0.5">Instalación</span>
                            <span class="block text-xs text-gray-500 font-normal">Montaje y configuración inicial</span>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-cyan-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </form>

            <!-- Botón Reemplazo -->
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                <input type="hidden" name="title" value="Sustitución / Reemplazo Físico">
                <input type="hidden" name="priority" value="high">
                <input type="hidden" name="task_type" value="replacement">
                <input type="hidden" name="description" value="Procedimiento de extracción y cambio de equipo por otro.">

                <button type="submit" class="w-full bg-[#12161f] hover:bg-white/5 border border-transparent hover:border-red-500/30 text-white font-black p-4 rounded-3xl flex items-center justify-between transition group shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-500/10 rounded-2xl flex items-center justify-center text-red-500 shrink-0 group-hover:bg-red-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-sm uppercase tracking-widest text-white mb-0.5">Reemplazo</span>
                            <span class="block text-xs text-gray-500 font-normal">Cambio físico por daño o mejora</span>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </form>
        </div>

    </div>
</x-technician-layout>
