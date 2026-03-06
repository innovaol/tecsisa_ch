<x-technician-layout hideHeader="true">
    <div class="fixed top-0 inset-x-0 z-[60] bg-[#0a0d14]/95 backdrop-blur-3xl border-b border-white/5 pt-safe">
        <div class="px-4 py-4 flex items-center justify-between mx-auto max-w-2xl">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white transition shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2">
                Catálogo de Activos
            </h1>
            <div class="w-10 h-10"></div>
        </div>
    </div>

    <div class="pt-24 px-5 max-w-2xl mx-auto md:py-10 md:px-0" x-data="{ 
        search: '',
        hasAnyMatch: true,
        matches(text) {
            if (!this.search) return true;
            return text.toLowerCase().includes(this.search.toLowerCase());
        },
        updateResults() {
            this.$nextTick(() => {
                let found = false;
                const items = Array.from(this.$refs.listContainer.querySelectorAll('.equipment-item'));
                if (this.search === '') {
                    found = true;
                } else {
                    found = items.some(el => el.getAttribute('data-search').includes(this.search.toLowerCase()));
                }
                this.hasAnyMatch = found;
            });
        }
    }" x-init="updateResults()">
        <!-- Search Bar (Tighter and Higher) -->
        <div class="bg-[#12161f]/90 backdrop-blur-xl border border-white/10 rounded-2xl p-3 mb-6 sticky top-[4.5rem] z-50 shadow-2xl">
            <div class="relative">
                <input type="text" x-model="search" @input="updateResults()" placeholder="Buscar equipo, serial o ID..." 
                       class="w-full bg-black/60 border border-white/10 rounded-xl px-10 py-2.5 text-white text-xs focus:ring-1 focus:ring-tecsisa-yellow outline-none transition uppercase font-bold tracking-tight">
                <svg class="absolute left-3 top-3 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <button x-show="search" @click="search = ''; updateResults();" class="absolute right-3 top-3 text-gray-500 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <div x-ref="listContainer">
            <!-- Título de Sección -->
            <div class="flex items-center justify-between mb-8 px-1">
                <h2 class="text-sm font-black text-white uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-tecsisa-yellow"></span> Explorar Inventario
                </h2>
                <span class="text-[9px] font-black text-gray-600 uppercase tracking-widest">{{ $locations->sum(fn($l) => $l->equipments->count()) }} Activos</span>
            </div>

            <!-- Location Groups -->
            @foreach($locations as $location)
                @if($location->equipments->count() > 0)
                    <div class="mb-10 location-group" 
                         x-data="{ 
                            localHasMatches() {
                                if(!search) return true;
                                return Array.from($el.querySelectorAll('.equipment-item')).some(el => {
                                    return el.getAttribute('data-search').includes(search.toLowerCase());
                                });
                            }
                         }"
                         x-show="!search || localHasMatches()">
                        
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-4 flex items-center gap-2 px-1">
                            <svg class="w-3.5 h-3.5 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $location->name }} 
                        </h3>

                        <div class="space-y-3">
                            @foreach($location->equipments as $eq)
                                @php
                                    $searchString = strtolower($eq->name . ' ' . $eq->internal_id . ' ' . ($eq->serial_number ?? ''));
                                @endphp
                                <a href="{{ route('technician.scanner.result', $eq->id) }}" 
                                   class="equipment-item block group" 
                                   data-search="{{ $searchString }}"
                                   x-show="matches('{{ $searchString }}')">
                                    <div class="bg-gradient-to-r from-[#12161f] to-[#0a0d14] border border-white/5 rounded-2xl p-4 flex items-center justify-between hover:border-tecsisa-yellow/20 transition active:scale-95 shadow-lg">
                                        <div class="flex items-center gap-4 min-w-0">
                                            <div class="w-10 h-10 bg-black/50 rounded-xl border border-white/5 flex items-center justify-center shrink-0 group-hover:bg-tecsisa-yellow/10 transition">
                                                <svg class="w-5 h-5 text-gray-500 group-hover:text-tecsisa-yellow transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2 mb-0.5">
                                                    <span class="text-tecsisa-yellow font-mono text-[9px] uppercase font-bold tracking-widest break-all">{{ $eq->internal_id }}</span>
                                                    <span class="text-[8px] bg-white/5 px-1.5 py-0.5 rounded text-gray-600 font-bold uppercase">{{ $eq->system->name ?? 'SC' }}</span>
                                                </div>
                                                <h4 class="text-sm font-bold text-white group-hover:text-tecsisa-yellow transition truncate px-0.5">{{ $eq->name }}</h4>
                                                @if($eq->serial_number)
                                                    <p class="text-[9px] text-gray-600 font-mono mt-0.5">S/N: {{ $eq->serial_number }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-700 group-hover:text-tecsisa-yellow shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Empty state when searching but no matches -->
            <div x-show="search && !hasAnyMatch" 
                 style="display: none;" 
                 class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-20 h-20 bg-red-500/10 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-red-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-white font-bold text-lg mb-2">Sin coincidencias</h3>
                <p class="text-gray-500 text-sm max-w-[200px] leading-relaxed uppercase font-black tracking-tighter">No encontramos equipos que coincidan con "<span x-text="search"></span>"</p>
            </div>
        </div>
    </div>


</x-technician-layout>
