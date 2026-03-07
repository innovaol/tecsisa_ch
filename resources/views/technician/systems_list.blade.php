<x-technician-layout hideHeader="true">
    <div class="fixed top-0 inset-x-0 z-[60] bg-theme-header backdrop-blur-3xl border-b border-theme pt-safe transition-colors duration-500">
        <div class="px-4 py-4 flex items-center justify-between mx-auto max-w-2xl">
            <a href="{{ route('technician.infrastructure') }}" class="w-10 h-10 rounded-full bg-theme-card border border-theme flex items-center justify-center text-gray-400 transition-colors shadow-lg" :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xs font-black uppercase tracking-widest flex items-center gap-2 transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                Sistemas Técnicos
            </h1>
            <div class="w-10 h-10"></div>
        </div>
    </div>

    <div class="pt-24 px-5 max-w-2xl mx-auto md:py-10 md:px-0">
        <div class="space-y-4">
            @foreach($systems as $sys)
                <div class="bg-theme-card border border-theme rounded-2xl p-4 flex flex-col hover:border-tecsisa-yellow/20 transition-all active:scale-95 shadow-lg group">
                    <div class="flex items-center justify-between w-full relative z-10">
                        <div class="flex items-center gap-4 min-w-0 flex-1">
                            <div class="w-12 h-12 bg-blue-500/10 rounded-2xl border border-blue-500/20 flex items-center justify-center shrink-0 group-hover:bg-blue-500/20 transition">
                                <span class="text-blue-400 font-bold text-lg font-mono">{{ substr($sys->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-black group-hover:text-blue-400 transition truncate" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $sys->name }}</h4>
                                <p class="text-[9px] text-gray-500 uppercase font-bold tracking-widest mt-0.5">ID: SYS-{{ str_pad($sys->id, 3, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end shrink-0 ml-3">
                            <span class="bg-blue-500/10 text-blue-400 px-2.5 py-1 rounded-full text-[9px] font-black uppercase border border-blue-500/20">
                                {{ $sys->equipments_count }} Activos
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($systems->isEmpty())
                <div class="text-center py-20 px-10">
                    <p class="text-gray-600 text-xs italic uppercase font-black tracking-widest leading-relaxed">No se han definido sistemas<br>técnicos todavía.</p>
                </div>
            @endif
    </div>


</x-technician-layout>
