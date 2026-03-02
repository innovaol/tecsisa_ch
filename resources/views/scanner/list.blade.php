<x-technician-layout hideHeader="true">
    <div class="fixed top-0 inset-x-0 z-[60] bg-[#0a0d14]/95 backdrop-blur-3xl border-b border-white/5 pt-safe">
        <div class="px-4 py-4 flex items-center justify-between">
            <a href="{{ route('technician.scanner') }}" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white transition shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xs font-black text-white uppercase tracking-widest">Coincidencias: {{ count($results) }}</h1>
            <div class="w-10 h-10"></div>
        </div>
    </div>

    <div class="pt-20 pb-28 px-5 relative z-10">
        <h2 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Buscando: "{{ $query }}"</h2>

        <div class="space-y-3">
            @forelse($results as $res)
                <form action="{{ route('technician.scanner.search') }}" method="POST" class="w-full">
                    @csrf
                    <input type="hidden" name="query" value="{{ $res->internal_id }}">
                    <button type="submit" class="w-full text-left bg-[#12161f] border border-white/5 hover:border-tecsisa-yellow/30 rounded-2xl p-4 flex items-center justify-between group transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-black/50 rounded-full border border-white/10 flex items-center justify-center shrink-0 group-hover:bg-tecsisa-yellow/10 group-hover:border-tecsisa-yellow/30 transition">
                                <svg class="w-5 h-5 text-gray-500 group-hover:text-tecsisa-yellow transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                            </div>
                            <div class="overflow-hidden">
                                <span class="block text-tecsisa-yellow font-mono text-[10px] uppercase font-bold tracking-widest mb-1">{{ $res->internal_id }}</span>
                                <h3 class="text-sm font-bold text-white truncate">{{ $res->name }}</h3>
                                <p class="text-xs text-gray-500 truncate">{{ $res->location ? $res->location->name : 'Sin ubicación' }}</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-tecsisa-yellow shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </form>
            @empty
                <div class="w-full flex flex-col items-center justify-center py-12 px-6 bg-[#12161f] rounded-3xl border border-dashed border-white/10 text-center">
                    <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-white text-lg">Sin Resultados</h3>
                    <p class="text-sm text-gray-500 mt-1">No se halló ningún equipo bajo ese parámetro.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-technician-layout>
