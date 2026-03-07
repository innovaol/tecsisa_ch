<x-technician-layout hideHeader="true">
    <div class="fixed top-0 inset-x-0 z-[60] bg-theme-header backdrop-blur-3xl border-b border-theme pt-safe transition-colors duration-500">
        <div class="px-4 py-4 flex items-center justify-between mx-auto max-w-2xl">
            <a href="{{ route('technician.infrastructure') }}" class="w-10 h-10 rounded-full bg-theme-card border border-theme flex items-center justify-center text-gray-400 transition-colors shadow-lg" :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xs font-black uppercase tracking-widest flex items-center gap-2 transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                Ubicaciones Físicas
            </h1>
            <div class="w-10 h-10"></div>
        </div>
    </div>

    <div class="pt-24 px-5 max-w-2xl mx-auto md:py-10 md:px-0">
        <div class="space-y-3">
            @foreach($locations as $loc)
                <div class="bg-theme-card border border-theme rounded-2xl p-4 flex items-center justify-between hover:border-tecsisa-yellow/20 transition-all active:scale-95 shadow-lg group">
                    <div class="flex items-center gap-4 min-w-0 flex-1">
                        <div class="w-12 h-12 bg-purple-500/10 rounded-2xl border border-purple-500/20 flex items-center justify-center shrink-0 group-hover:bg-purple-500/20 transition">
                            <svg class="w-6 h-6 text-purple-400 group-hover:text-purple-300 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-black group-hover:text-purple-400 transition truncate" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $loc->name }}</h4>
                            <p class="text-[9px] text-gray-500 font-bold uppercase tracking-tight">Activos vinculados: {{ $loc->equipments_count }}</p>
                            @if($loc->parent_id)
                                <span class="text-[8px] bg-white/5 px-1.5 py-0.5 rounded text-gray-600 font-bold uppercase mt-1 inline-block">JERARQUÍA NIVEL {{ $loc->level ?? '2+' }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="bg-white/5 border border-white/10 p-2 rounded-xl text-gray-400 transition" :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                    </div>
                </div>
            @endforeach

            @if($locations->isEmpty())
                <div class="text-center py-20 px-10">
                    <p class="text-gray-600 text-xs italic uppercase font-black tracking-widest leading-relaxed">No hay ubicaciones<br>físicas creadas.</p>
                </div>
            @endif
    </div>


</x-technician-layout>
