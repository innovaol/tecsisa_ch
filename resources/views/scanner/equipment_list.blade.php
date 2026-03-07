<x-technician-layout>
    <div class="py-6 md:py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" x-data="{ 
        search: '',
        matches(text) {
            if (!this.search) return true;
            return text.toLowerCase().includes(this.search.toLowerCase());
        },
        hasAnyMatch() {
            if (!this.search) return true;
            return Array.from(document.querySelectorAll('.equipment-item')).some(el => 
                el.getAttribute('data-search').includes(this.search.toLowerCase())
            );
        }
    }">
        <!-- Header Section: Tarjeta Propia -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 mb-8 transition-all duration-500 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-tecsisa-yellow/5 rounded-full blur-2xl"></div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div>
                    <h2 class="text-3xl font-black transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                        Catálogo de <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Activos</span>
                    </h2>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-2 px-1">Exploración técnica y consulta de inventario hospitalario</p>
                </div>
                
                <div class="relative w-full md:w-96 group">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400 group-focus-within:text-tecsisa-yellow transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" x-model="search" placeholder="Buscar ID, nombre o serial..." 
                           class="w-full bg-black/10 border border-theme text-xs font-bold uppercase tracking-wider rounded-2xl pl-12 pr-4 py-4 focus:ring-1 focus:ring-tecsisa-yellow/30 focus:border-tecsisa-yellow/50 transition-all duration-300 placeholder-gray-600 outline-none shadow-xl transition-colors" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                </div>
            </div>
        </div>

        <!-- Unified Responsive Inventory View -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
            <!-- Table Header (Desktop Only) -->
            <div class="hidden md:grid grid-cols-12 bg-black/10 border-b border-theme px-8 py-5 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] items-center">
                <div class="col-span-2">ID Interno</div>
                <div class="col-span-4">Equipo / Activo</div>
                <div class="col-span-3">Ubicación de Site</div>
                <div class="col-span-1 text-center">Sistema</div>
                <div class="col-span-1 text-center">Estado</div>
                <div class="col-span-1 text-right">Acción</div>
            </div>

            <div class="divide-y divide-white/5">
                @foreach($locations as $location)
                    @foreach($location->equipments as $eq)
                        @php
                            $searchString = strtolower($eq->name . ' ' . $eq->internal_id . ' ' . ($eq->serial_number ?? '') . ' ' . $location->name);
                        @endphp
                        <div class="equipment-item group transition-all" 
                             data-search="{{ $searchString }}"
                             x-show="matches('{{ $searchString }}')">
                            
                            <!-- DESKTOP LAYOUT (Row) -->
                            <div class="hidden md:grid grid-cols-12 px-8 py-6 items-center hover:bg-black/5 transition-colors border-b border-theme last:border-0">
                                <div class="col-span-2 text-xs font-black text-tecsisa-yellow font-mono tracking-widest">{{ $eq->internal_id }}</div>
                                <div class="col-span-4 flex flex-col">
                                    <span class="text-xs font-black transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $eq->name }}</span>
                                    <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-1">SN: {{ $eq->serial_number ?? 'N/A' }}</span>
                                </div>
                                <div class="col-span-3 text-[10px] text-gray-500 font-bold uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    {{ $location->name }}
                                </div>
                                <div class="col-span-1 flex justify-center">
                                    <span class="text-[9px] bg-theme-border border border-theme px-3 py-1 rounded-full text-gray-500 font-black uppercase tracking-widest transition-colors duration-500">
                                        {{ $eq->system->name ?? 'SC' }}
                                    </span>
                                </div>
                                <div class="col-span-1 flex justify-center">
                                    @if($eq->status === 'operative')
                                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]" title="Operativo"></div>
                                    @elseif($eq->status === 'under_maintenance')
                                        <div class="w-2.5 h-2.5 rounded-full bg-yellow-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]" title="Mantenimiento"></div>
                                    @else
                                        <div class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]" title="Fallo"></div>
                                    @endif
                                </div>
                                <div class="col-span-1 text-right">
                                    <a href="{{ route('technician.scanner.result', $eq->id) }}" class="inline-flex p-3 bg-theme-border hover:bg-tecsisa-yellow hover:text-black border border-theme rounded-2xl transition-all duration-300 shadow-lg active:scale-95 group/btn">
                                        <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </div>
                            </div>

                            <!-- MOBILE LAYOUT (Card) -->
                            <a href="{{ route('technician.scanner.result', $eq->id) }}" class="md:hidden flex items-center justify-between p-5 border-b border-theme active:bg-black/5 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-black/10 rounded-2xl flex items-center justify-center border border-theme text-tecsisa-yellow transition-all duration-300 group-active:scale-90 overflow-hidden relative shadow-inner">
                                        <div class="absolute inset-0 bg-tecsisa-yellow/5"></div>
                                        <svg class="w-7 h-7 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <span class="text-[10px] font-black text-tecsisa-yellow font-mono uppercase tracking-widest">{{ $eq->internal_id }}</span>
                                            <span class="text-[9px] bg-theme-border p-1 px-2 rounded-lg text-gray-500 font-bold uppercase tracking-widest transition-colors duration-500">{{ $eq->system->name ?? 'SC' }}</span>
                                        </div>
                                        <h4 class="text-[14px] font-black leading-tight truncate pr-8 transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $eq->name }}</h4>
                                        <div class="flex items-center gap-2 mt-2">
                                            <div class="w-1.5 h-1.5 rounded-full {{ $eq->status === 'operative' ? 'bg-emerald-500' : 'bg-red-500' }}"></div>
                                            <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">{{ $location->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6 text-gray-700 group-active:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        <!-- Static Empty State -->
        <div x-show="!hasAnyMatch()" style="display: none;" class="py-24 flex flex-col items-center animate-fadeIn">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-6 border border-white/5">
                <svg class="w-10 h-10 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-500">Sin coincidencias para "<span :class="theme === 'light' ? 'text-slate-800' : 'text-white'" x-text="search"></span>"</p>
        </div>
    </div>
</x-technician-layout>
