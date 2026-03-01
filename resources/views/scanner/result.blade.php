<x-technician-layout>
    <div class="fixed top-0 inset-x-0 z-40 bg-[#0a0d14]/90 backdrop-blur-xl border-b border-white/5 pt-safe">
        <div class="px-4 py-3 flex items-center justify-between">
            <a href="{{ route('technician.scanner') }}" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> Equipo B.D.R
            </h1>
            <div class="w-10 h-10"></div>
        </div>
    </div>

    <div class="pt-20 pb-24 px-5">
        
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

        <!-- Acción Principal -->
        <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">Acción Requerida</h3>
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <!-- Este técnico inicia intervención local -->
            <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
            <input type="hidden" name="title" value="Intervención Proactiva / Inspección Local">
            <input type="hidden" name="priority" value="low">
            <input type="hidden" name="description" value="Tarea creada en sitio por el técnico desde el escáner móvil.">

            <button type="submit" class="w-full bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black py-5 rounded-2xl text-sm uppercase tracking-widest shadow-[0_10px_30px_rgba(255,209,0,0.3)] transition transform active:scale-95 flex items-center justify-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Iniciar Intervención Inmediata
            </button>
        </form>

    </div>
</x-technician-layout>
