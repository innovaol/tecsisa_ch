@props(['location', 'allRacks'])

<div x-data="{ open: false }" class="mb-2">
    <!-- Location Card -->
    <div class="bg-theme-card border border-theme p-4 rounded-2xl flex justify-between items-center group hover:border-tecsisa-yellow/30 transition-all duration-500 shadow-md">
        <div class="flex items-center gap-4">
            @if($location->children->count() > 0 || $allRacks->where('location_id', $location->id)->count() > 0)
                <button @click="open = !open" class="text-theme-muted hover:text-tecsisa-yellow transition-transform duration-200" :class="open ? 'rotate-90' : ''">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>
            @else
                <div class="w-4"></div>
            @endif
            
            <div @click="open = !open" class="cursor-pointer">
                <h4 class="font-black text-[11px] uppercase tracking-widest text-theme" x-text="'{{ $location->name }}'"></h4>
                <div class="flex gap-3 mt-1">
                    <span class="text-[9px] text-theme-muted uppercase tracking-tighter font-bold">
                        {{ $location->children->count() }} sub-niveles
                    </span>
                    <span class="text-[9px] text-tecsisa-yellow uppercase tracking-tighter font-black">
                        {{ $allRacks->where('location_id', $location->id)->count() }} Racks
                    </span>
                </div>
            </div>
        </div>

        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <button @click="openEditLocationModal(@js($location))" class="text-gray-500 transition p-1" :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </button>
            <form action="{{ route('catalog.locations.destroy', $location) }}" method="POST" onsubmit="return confirm('¿Eliminar esta ubicación?')">
                @csrf @method('DELETE')
                <button class="text-gray-500 hover:text-red-400 transition p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Recursive Children & Racks -->
    <div x-show="open" x-collapse x-transition class="ml-6 mt-3 pl-6 border-l-2 border-theme/20 space-y-3">
        <!-- Sub-Locations -->
        @foreach($location->children as $child)
            <x-location-item :location="$child" :allRacks="$allRacks" />
        @endforeach

        <!-- Racks in this specific location -->
        @foreach($allRacks->where('location_id', $location->id) as $rack)
            <div class="bg-theme/5 border border-theme p-5 rounded-2xl group hover:border-tecsisa-yellow/40 transition-all flex justify-between items-center shadow-sm">
                <div>
                    <h5 class="font-black text-[10px] flex items-center gap-2 uppercase tracking-widest text-theme">
                         <svg class="w-4 h-4 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                         {{ $rack->name }}
                    </h5>
                    <p class="text-[9px] text-theme-muted uppercase font-bold tracking-widest mt-1">{{ $rack->total_units }}U • {{ $rack->status }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('rack.builder', ['rack_id' => $rack->id]) }}" class="p-2 text-theme-muted hover:text-tecsisa-yellow transition-colors" title="Ver Rack">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </a>
                    <button @click="openEditRackModal(@js($rack))" class="text-theme-muted hover:text-theme transition p-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <form action="{{ route('catalog.racks.destroy', $rack) }}" method="POST" onsubmit="return confirm('¿Eliminar este rack?')">
                        @csrf @method('DELETE')
                        <button class="text-theme-muted hover:text-red-400 transition p-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach

        @if($location->children->count() === 0 && $allRacks->where('location_id', $location->id)->count() === 0)
            <p class="text-[10px] text-gray-700 italic py-1">Ubicación vacía</p>
        @endif
    </div>
</div>
