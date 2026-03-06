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
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white leading-tight">
                    Catálogo de <span class="text-tecsisa-yellow uppercase tracking-widest text-xs font-black">Activos</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1 px-1">
                    Exploración técnica y consulta de inventario hospitalario
                </p>
            </div>
            
            <div class="relative w-full md:w-80">
                <input type="text" x-model="search" placeholder="Buscar ID, nombre o serial..." 
                       class="w-full bg-black/40 border-2 border-white/10 text-xs font-bold text-white uppercase tracking-wider rounded-xl pl-10 pr-4 py-3 focus:ring-tecsisa-yellow focus:border-tecsisa-yellow transition-colors placeholder-gray-600 outline-none shadow-xl">
                <svg class="w-4 h-4 text-gray-500 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <!-- Desktop View: Table (Hidden on mobile) -->
        <div class="hidden md:block bg-[#0f1217]/50 backdrop-blur-xl border border-white/5 rounded-3xl overflow-hidden shadow-2xl">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/[0.02] border-b border-white/5">
                        <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">ID Interno</th>
                        <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Equipo / Activo</th>
                        <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Ubicación</th>
                        <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Sistema</th>
                        <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Estado</th>
                        <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] text-right">Ver Detalle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($locations as $location)
                        @foreach($location->equipments as $eq)
                            @php
                                $searchString = strtolower($eq->name . ' ' . $eq->internal_id . ' ' . ($eq->serial_number ?? '') . ' ' . $location->name);
                            @endphp
                            <tr class="equipment-item hover:bg-white/[0.02] transition-colors" 
                                data-search="{{ $searchString }}"
                                x-show="matches('{{ $searchString }}')">
                                <td class="px-6 py-4 text-xs font-black text-tecsisa-yellow font-mono tracking-widest">
                                    {{ $eq->internal_id }}
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-gray-200">
                                    {{ $eq->name }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400 font-bold uppercase tracking-tight">
                                    {{ $location->name }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <span class="text-[10px] bg-white/5 border border-white/5 px-2 py-1 rounded text-gray-500 font-black uppercase">
                                        {{ $eq->system->name ?? 'SC' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($eq->status === 'operative')
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                                            <span class="text-[9px] font-black text-emerald-500 uppercase">Operativo</span>
                                        @elseif($eq->status === 'under_maintenance')
                                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                            <span class="text-[9px] font-black text-yellow-500 uppercase">En Mantenimiento</span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                            <span class="text-[9px] font-black text-red-500 uppercase">Fallo</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('technician.scanner.result', $eq->id) }}" class="inline-flex p-2 bg-white/5 hover:bg-tecsisa-yellow hover:text-black rounded-lg transition overflow-hidden">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile View: Cards (Visible on mobile) -->
        <div class="md:hidden space-y-4">
            @foreach($locations as $location)
                <div class="mb-6" x-show="!search || Array.from($el.querySelectorAll('.equipment-card')).some(el => el.getAttribute('data-search').includes(search.toLowerCase()))">
                    <h3 class="text-[10px] font-black text-gray-600 uppercase tracking-[0.3em] mb-4 flex items-center gap-2 px-1">
                        <svg class="w-3 h-3 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ $location->name }}
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach($location->equipments as $eq)
                            @php
                                $searchString = strtolower($eq->name . ' ' . $eq->internal_id . ' ' . ($eq->serial_number ?? ''));
                            @endphp
                            <a href="{{ route('technician.scanner.result', $eq->id) }}" 
                               class="equipment-card block equipment-item" 
                               data-search="{{ $searchString }}"
                               x-show="matches('{{ $searchString }}')">
                                <div class="bg-gradient-to-br from-[#12161f] to-[#0a0d14] border border-white/5 rounded-2xl p-4 flex items-center justify-between active:scale-95 transition-all shadow-xl">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-black/50 rounded-xl flex items-center justify-center border border-white/5 text-tecsisa-yellow">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-0.5">
                                                <span class="text-[9px] font-black text-tecsisa-yellow font-mono uppercase tracking-widest">{{ $eq->internal_id }}</span>
                                                <span class="text-[8px] bg-white/5 px-1 py-0.5 rounded text-gray-500 font-bold">{{ $eq->system->name ?? 'SC' }}</span>
                                            </div>
                                            <h4 class="text-sm font-black text-white leading-tight truncate max-w-[150px]">{{ $eq->name }}</h4>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        <div x-show="!hasAnyMatch()" style="display: none;" class="py-20 flex flex-col items-center opacity-40">
            <svg class="w-12 h-12 text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-xs font-black uppercase tracking-widest text-white">No se encontraron activos</p>
        </div>
    </div>
</x-technician-layout>


</x-technician-layout>
